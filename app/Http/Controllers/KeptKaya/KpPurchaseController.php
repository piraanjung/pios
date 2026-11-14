<?php 

namespace App\Http\Controllers\KeptKaya; 

use App\Http\Controllers\Controller; 
use App\Models\Admin\Organization; 
use App\Models\KeptKaya\KpPurchaseRoute; 
use App\Models\KeptKaya\KpPurchaseRouteZone; 
use App\Models\KeptKaya\KpPurchaseTransactionDetail; 
use App\Models\KeptKaya\KpPurchaseTransaction; 
use App\Models\KeptKaya\KpTbankItems; 
use App\Models\KeptKaya\KpTbankItemsGroups; 
use App\Models\KeptKaya\KpTbankItemsPriceAndPoint; 
use App\Models\KeptKaya\KpTbankUnits; 
use App\Models\KeptKaya\Machine; 
use App\Models\KeptKaya\KpRecycleBankMember; // เรียกใช้ Model ใหม่
use App\Models\KPBankAccount;
use App\Models\User; 
use Carbon\Carbon; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Str; 

class KpPurchaseController extends Controller 
{ 
    public function select_user(Request $request) 
    { 
        $request->session()->forget(['purchase_user_id', 'purchase_cart']); 
        $today = Carbon::now()->toDateString(); 
        $orgId = Auth::user()->org_id_fk; 
        $searchKey = trim($request->input('keyword', $request->input('name_search', $request->input('username_search')))); 

        // ดึงเฉพาะ User ที่มีข้อมูลลงทะเบียนใน kp_recycle_bank_members ของ อปท. นี้
        $query = User::query() 
            ->select(['id', 'org_id_fk', 'username', 'firstname', 'lastname', 'phone', 'id_card', 'zone_id', 'image']) 
            ->where('org_id_fk', $orgId) 
            ->whereHas('kpRecycleBankMember', function ($q) use ($orgId) { 
                $q->where('org_id_fk', $orgId)->where('status', 'active'); 
            }) 
            ->with([ 
                'kpRecycleBankMember', 
                'user_zone:id,zone_name', 
                'purchaseTransactions' => function ($q) use ($today) { 
                    $q->whereDate('transaction_date', $today); 
                } 
            ]); 

        if (session()->has('purchase_route_id') && !$request->boolean('search_all_zones')) { 
            $routeId = session('purchase_route_id'); 
            $zoneIds = KpPurchaseRouteZone::where('kp_purchase_route_id', $routeId)->pluck('zone_id'); 
            $query->whereIn('zone_id', $zoneIds); 
        } 

        if (!empty($searchKey)) { 
            $query->where(function ($q) use ($searchKey) { 
                $q->where('id', $searchKey) 
                  ->orWhere('username', $searchKey) 
                  ->orWhere('phone', $searchKey) 
                  ->orWhere('id_card', $searchKey) 
                  ->orWhere('firstname', 'like', "%{$searchKey}%") 
                  ->orWhere('lastname', 'like', "%{$searchKey}%"); 
            }); 
        } 

        $keptKayaMembers = $query->orderBy('firstname')->orderBy('lastname')->get(); 
        return view('keptkayas.purchase.select_user', compact('keptKayaMembers')); 
    } 

    public function startPurchase($userId) 
    { 
        $orgId = Auth::user()->org_id_fk; 
        $user = User::where('id', $userId) 
            ->where('org_id_fk', $orgId) 
            ->whereHas('kpRecycleBankMember', function($q) use ($orgId) { 
                $q->where('org_id_fk', $orgId)->where('status', 'active'); 
            }) 
            ->firstOrFail(); 

        session(['purchase_user_id' => $user->id]); 
        return redirect()->route('keptkayas.purchase.form', $user->id); 
    } 

    public function showPurchaseForm(Request $request, $user_id) 
    { 
        $orgId = Auth::user()->org_id_fk; 
        $user = User::where('id', $user_id) 
            ->where('org_id_fk', $orgId) 
            ->with('kpRecycleBankMember')
            ->first(); 

        if (!$user) { 
            return redirect()->route('keptkayas.purchase.select_user') 
                ->with('error', 'ไม่พบข้อมูลผู้ใช้งานในระบบ'); 
        } 

        if (!$user->kpRecycleBankMember || $user->kpRecycleBankMember->status !== 'active') { 
            return redirect()->route('keptkayas.purchase.select_user') 
                ->with('error', 'ผู้ใช้งานนี้ยังไม่ได้เป็นสมาชิกธนาคารขยะ หรือบัญชีถูกระงับ'); 
        } 

        $request->session()->put('purchase_user_id', $user->id); 
        $recycleItems = KpTbankItems::with(['activePrices.kp_units_info', 'emissionFactor']) 
            ->where('org_id_fk', $orgId) 
            ->whereHas('activePrices.kp_units_info') 
            ->get(); 

        $allUnits = KpTbankUnits::where('org_id_fk', $orgId)->get(); 
        $itemsGroups = KpTbankItemsGroups::where('status', 'active')->get(); 

        return view('keptkayas.purchase.purchase_form', compact('user', 'recycleItems', 'allUnits', 'itemsGroups')); 
    } 

    public function addToCart(Request $request) 
    { 
        $validated = $request->validate([ 
            'user_id' => 'required|exists:users,id', 
            'kp_tbank_item_id' => 'required|exists:kp_tbank_items,id', 
            'amount_in_units' => 'required|numeric|min:0.01', 
            'kp_units_idfk' => 'required|exists:kp_tbank_items_units,id', 
        ]); 

        $item = KpTbankItems::find($validated['kp_tbank_item_id']); 
        $unit = KpTbankUnits::find($validated['kp_units_idfk']); 
        $priceConfig = KpTbankItemsPriceAndPoint::where('kp_items_idfk', $item->id) 
            ->where('kp_units_idfk', $unit->id) 
            ->where('status', 'active') 
            ->where('deleted', '0') 
            ->first(); 

        if (!$priceConfig) { 
            return back()->with('error', 'ไม่พบราคารับซื้อที่ใช้งานอยู่สำหรับขยะและหน่วยนับนี้')->withInput(); 
        } 

        $amount = $validated['amount_in_units'] * $priceConfig->price_for_member; 
        $points = $validated['amount_in_units'] * $priceConfig->point; 

        $cartItem = [ 
            'kp_tbank_item_id' => $item->id, 
            'item_name' => $item->kp_itemsname, 
            'kp_units_idfk' => $unit->id, 
            'unit_name' => $unit->unitname, 
            'amount_in_units' => $validated['amount_in_units'], 
            'price_per_unit' => $priceConfig->price_for_member, 
            'amount' => $amount, 
            'points' => $points, 
            'kp_tbank_items_pricepoint_id' => $priceConfig->id, 
        ]; 

        $userId = $validated['user_id'];
        Session::put('purchase_user_id', $userId);
        $userCartKey = "purchase_cart_{$userId}";
        $userCart = Session::get($userCartKey, []);
        $userCart[] = $cartItem;
        Session::put($userCartKey, $userCart);
        Session::put('purchase_cart', $userCart);

        return back()->with('success', 'เพิ่มรายการขยะลงในรถเข็นแล้ว'); 
    } 

    public function showCart(Request $request) 
    { 
        $userId = $request->query('user_id') ?: Session::get('purchase_user_id'); 

        if (!$userId) { 
            return redirect()->route('keptkayas.purchase.select_user') 
                ->with('warning', 'กรุณาเลือกผู้ขายก่อนเปิดตะกร้า'); 
        } 

        $cart = Session::get("purchase_cart_{$userId}", Session::get('purchase_cart', [])); 
        $seller = User::where('id', $userId)->with('user_zone')->firstOrFail(); 
        $user = Auth::user(); 

        return view('keptkayas.purchase.cart', compact('cart', 'user', 'seller')); 
    } 

    public function saveTransaction(Request $request) 
    { 
        // Idempotency Token check (prevent duplicate credit / network retries even if cart was already cleared)
        $token = $request->input('transaction_token');
        if ($token && $cachedId = Cache::get("purchase_tx_{$token}")) {
            return redirect()->route('keptkayas.purchase.receipt', $cachedId)
                ->with('info', 'รายการนี้ได้รับการบันทึกแล้ว (ป้องกันการบันทึกซ้ำ)');
        }

        $userId = $request->input('user_id', Session::get('purchase_user_id')); 
        $cart = Session::get("purchase_cart_{$userId}", Session::get('purchase_cart', [])); 

        if (empty($cart) || !$userId) { 
            return redirect()->route('keptkayas.purchase.select_user')->with('error', 'ไม่พบรายการในรถเข็น'); 
        }

        return DB::transaction(function () use ($request, $cart, $userId, $token) { 
            $seller = User::findOrFail($userId); 
            $recorder = Auth::user(); 
            $totalWeight = array_sum(array_column($cart, 'amount_in_units')); 
            $totalAmount = array_sum(array_column($cart, 'amount')); 
            $totalPoints = array_sum(array_column($cart, 'points')); 
            $isCashBack = $request->has('cash_back') ? 1 : 0; 
            $transNo = 'T-' . Carbon::now()->format('ymdHis') . str_pad($userId, 4, '0', STR_PAD_LEFT) . strtoupper(Str::random(3)); 

            $transaction = KpPurchaseTransaction::create([ 
                'kp_u_trans_no' => $transNo, 
                'org_id_fk' => $recorder->org_id_fk, 
                'kp_purchase_route_id' => session('purchase_route_id'), 
                'user_id' => $userId, 
                'transaction_date' => Carbon::now()->toDateString(), 
                'total_weight' => $totalWeight, 
                'total_amount' => $totalAmount, 
                'total_points' => $totalPoints, 
                'recorder_id' => $recorder->id, 
                'status' => 1, 
                'cash_back' => $isCashBack 
            ]); 

            $carbonSavedTotal = 0; 
            foreach ($cart as $item) { 
                $itemModel = KpTbankItems::with('emissionFactor')->find($item['kp_tbank_item_id']); 
                $ef = $itemModel->emissionFactor->ef_value ?? 0; 
                $carbonSaved = $item['amount_in_units'] * $ef; 
                $carbonSavedTotal += $carbonSaved; 

                KpPurchaseTransactionDetail::create([ 
                    'kp_purchase_trans_id' => $transaction->id, 
                    'org_id_fk' => $recorder->org_id_fk, 
                    'kp_u_trans_no' => $transaction->kp_u_trans_no, 
                    'kp_recycle_item_id' => $item['kp_tbank_item_id'], 
                    'kp_tbank_items_pricepoint_id' => $item['kp_tbank_items_pricepoint_id'] ?? null, 
                    'kp_units_idfk' => $item['kp_units_idfk'], 
                    'amount_in_units' => $item['amount_in_units'], 
                    'price_per_unit' => $item['price_per_unit'], 
                    'amount' => $item['amount'], 
                    'points' => $item['points'], 
                    'carbon_saved' => $carbonSaved, 
                    'recorder_id' => $recorder->id 
                ]); 
            } 

            $transaction->update(['total_carbon_saved' => $carbonSavedTotal]); 

            // Concurrency Lock: Lock both ledgers to prevent race conditions & out-of-sync balances
            $recycleMember = KpRecycleBankMember::where('user_id', $userId)
                ->where('org_id_fk', $recorder->org_id_fk)
                ->lockForUpdate()
                ->first();

            $bankAccount = KPBankAccount::where('user_id', $userId)
                ->where('org_id_fk', $recorder->org_id_fk)
                ->lockForUpdate()
                ->first();

            if (!$bankAccount) {
                $accountNo = 'ACC-' . ($recorder->org_id_fk) . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
                $bankAccount = KPBankAccount::create([
                    'user_id' => $userId,
                    'org_id_fk' => $recorder->org_id_fk,
                    'account_no' => $accountNo,
                    'member_number' => $recycleMember?->member_number,
                    'balance' => $recycleMember ? $recycleMember->balance : 0,
                    'points' => $recycleMember ? (int)$recycleMember->total_points : 0,
                    'status' => 'active',
                    'entity_type' => 'recycle_bank_member',
                ]);
            }

            if ($recycleMember) {
                $recycleMember->increment('total_points', $totalPoints); 
                if ($isCashBack == 0) { 
                    $recycleMember->increment('balance', $totalAmount); 
                } 
            }

            if ($bankAccount) {
                $bankAccount->increment('points', (int)$totalPoints);
                if ($isCashBack == 0) {
                    $bankAccount->increment('balance', $totalAmount);
                }
            }

            if ($token) {
                Cache::put("purchase_tx_{$token}", $transaction->id, now()->addMinutes(15));
            }

            Session::forget(["purchase_cart_{$userId}", 'purchase_cart', 'purchase_user_id']); 
            return redirect()->route('keptkayas.purchase.receipt', $transaction->id) 
                ->with('success', 'บันทึกสำเร็จ! ลดคาร์บอนได้ ' . number_format($carbonSavedTotal, 4) . ' kgCO2e'); 
        }); 
    } 

    public function removeFromCart(Request $request, $index) 
    { 
        $userId = $request->input('user_id', Session::get('purchase_user_id')); 
        $userCartKey = "purchase_cart_{$userId}";
        $cart = Session::get($userCartKey, Session::get('purchase_cart', [])); 
        if (isset($cart[$index])) { 
            unset($cart[$index]); 
            $newCart = array_values($cart);
            Session::put($userCartKey, $newCart);
            Session::put('purchase_cart', $newCart); 
            return back()->with('success', 'ลบรายการขยะออกจากรถเข็นแล้ว'); 
        } 
        return back()->with('error', 'ไม่พบรายการที่ต้องการลบ'); 
    } 

    public function clearCart(Request $request) 
    { 
        $userId = $request->input('user_id', Session::get('purchase_user_id')); 
        Session::forget(["purchase_cart_{$userId}", 'purchase_cart', 'purchase_user_id']); 
        return redirect()->route('keptkayas.purchase.select_user'); 
    } 

    public function showPurchaseHistory($user_id) 
    { 
        $userHistory = User::where('id', $user_id) 
            ->with([ 
                'purchaseTransactions.details.item', 
                'purchaseTransactions.details.pricePoint.kp_units_info' 
            ]) 
            ->firstOrFail(); 

        return view('keptkayas.purchase.history', compact('userHistory')); 
    } 

    public function showReceipt($transaction_id) 
    { 
        $transaction = KpPurchaseTransaction::where('id', $transaction_id) 
            ->with('user.kpRecycleBankMember', 'details.item', 'details.pricePoint.kp_units_info') 
            ->firstOrFail(); 

        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk); 
        return view('keptkayas.purchase.receipt', compact('transaction', 'orgInfos')); 
    } 

    public function connect_bluethooth() 
    { 
        $transaction = KpPurchaseTransaction::latest() 
            ->with('user.kpRecycleBankMember', 'details.item', 'details.pricePoint.kp_units_info') 
            ->first(); 

        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk); 
        return view('keptkayas.purchase.connect_bluethooth', compact('transaction', 'orgInfos')); 
    } 

    public function getUnitsForItem($itemId) 
    { 
        $unitsAndPrices = KpTbankItemsPriceAndPoint::where('kp_items_idfk', $itemId) 
            ->where('status', 'active') 
            ->where('deleted', '0') 
            ->with('kp_units_info') 
            ->get(); 

        $data = []; 
        foreach ($unitsAndPrices as $priceEntry) { 
            $data[] = [ 
                'unit_id' => $priceEntry->kp_units_idfk, 
                'unit_name' => $priceEntry->kp_units_info->unitname ?? '-', 
                'price_for_member' => $priceEntry->price_for_member, 
                'point' => $priceEntry->point, 
            ]; 
        } 
        return response()->json($data); 
    } 

    public function selectRoute(Request $request) 
    { 
        if ($request->isMethod('post')) { 
            $routeId = $request->input('route_id'); 
            if ($routeId === 'all') { 
                session()->forget(['purchase_route_id', 'purchase_route_name']); 
            } else { 
                $route = KpPurchaseRoute::find($routeId); 
                if ($route) { 
                    session([ 
                        'purchase_route_id' => $route->id, 
                        'purchase_route_name' => $route->route_name, 
                    ]); 
                } 
            } 
            return redirect()->route('keptkayas.purchase.select_user') 
                ->with('success', 'เปลี่ยนเขตการรับซื้อเรียบร้อยแล้ว'); 
        } 

        $orgId = Auth::user()->org_id_fk; 
        $routes = KpPurchaseRoute::where('org_id_fk', $orgId) 
            ->where('status', 'active') 
            ->with('zones') 
            ->get(); 

        $currentRouteId = session('purchase_route_id', 'all'); 
        return view('keptkayas.purchase.select_route', compact('routes', 'currentRouteId')); 
    } 

    public function routeIndex() 
    { 
        $orgId = Auth::user()->org_id_fk; 
        $routes = KpPurchaseRoute::where('org_id_fk', $orgId) 
            ->with('zones') 
            ->orderBy('id', 'desc') 
            ->get(); 

        $assignedZoneIds = KpPurchaseRouteZone::whereHas('route', function($q) use ($orgId) { 
            $q->where('org_id_fk', $orgId); 
        })->pluck('zone_id')->toArray(); 

        $availableZones = \App\Models\Admin\Zone::where('org_id_fk', $orgId) 
            ->whereNotIn('id', $assignedZoneIds) 
            ->get(); 

        $allZones = \App\Models\Admin\Zone::where('org_id_fk', $orgId)->get(); 

        return view('keptkayas.purchase.routes_manage', compact('routes', 'availableZones', 'allZones', 'assignedZoneIds')); 
    } 

    public function routeSave(Request $request) 
    { 
        $request->validate([ 
            'route_name' => 'required|string|max:255', 
            'zone_ids' => 'nullable|array', 
            'zone_ids.*' => 'exists:zones,id', 
        ], [ 
            'route_name.required' => 'กรุณากรอกชื่อเขตรับซื้อ', 
        ]); 

        $orgId = Auth::user()->org_id_fk; 
        $routeId = $request->input('route_id'); 

        DB::transaction(function () use ($request, $orgId, $routeId) { 
            $route = KpPurchaseRoute::updateOrCreate( 
                ['id' => $routeId, 'org_id_fk' => $orgId], 
                [ 
                    'route_name' => $request->input('route_name'), 
                    'status' => $request->input('status', 'active'), 
                ] 
            ); 

            $zoneIds = $request->input('zone_ids', []); 
            $route->zones()->sync($zoneIds); 
        }); 

        return back()->with('success', 'บันทึกข้อมูลเขตการรับซื้อเรียบร้อยแล้ว'); 
    } 

    public function routeDelete($id) 
    { 
        $orgId = Auth::user()->org_id_fk; 
        $route = KpPurchaseRoute::where('id', $id) 
            ->where('org_id_fk', $orgId) 
            ->first(); 

        if ($route) { 
            $route->delete(); 
            return back()->with('success', 'ลบเขตการรับซื้อเรียบร้อยแล้ว'); 
        } 
        return back()->with('error', 'ไม่พบข้อมูลเขตที่ต้องการลบ'); 
    } 

    public function saveTransactionForMachine(Request $request) 
    { 
        $request->validate([ 
            'acceptedBottles' => 'required|array|min:1', 
            'acceptedBottles.*.user_id' => 'required|numeric|exists:users,id', 
            'acceptedBottles.*.kp_tbank_item_id' => 'required|numeric', 
            'acceptedBottles.*.kp_tbank_items_pricepoint_id' => 'required|numeric', 
            'acceptedBottles.*.amount_in_units' => 'required|numeric|min:0', 
            'acceptedBottles.*.price_per_unit' => 'required|numeric|min:0', 
            'acceptedBottles.*.amount' => 'required|numeric|min:0', 
            'acceptedBottles.*.points' => 'required|numeric|min:0', 
            'acceptedBottles.*.unit_name' => 'required|string', 
        ]); 

        $acceptedBottles = $request->input('acceptedBottles'); 
        $userId = $acceptedBottles[0]['user_id']; 
        $recorderId = Auth::id(); 

        $user = User::find($userId); 
        if (!$user) { 
            return response()->json(['error' => 'Customer User ID not found.'], 204); 
        } 

        // Managed automatically by SetTenantConnection 

        $totalWeight = 0; 
        $totalAmount = 0; 
        $totalPoints = 0; 

        foreach ($acceptedBottles as $bottle) { 
            $totalWeight += floatval($bottle['amount_in_units']); 
            $totalAmount += floatval($bottle['amount']); 
            $totalPoints += floatval($bottle['points']); 
        } 

        DB::beginTransaction(); 
        try { 
            $machine = Machine::where('machine_id', $acceptedBottles[0]['machine_id'])->first(); 

            $transaction = KpPurchaseTransaction::create([ 
                'kp_u_trans_no' => 'M-' . Carbon::now()->format('YmdHis') . Str::random(3), 
                'org_id_fk' => $user->org_id_fk, 
                'user_id' => $userId, 
                'transaction_date' => date('Y-m-d'), 
                'total_weight' => $totalWeight, 
                'total_amount' => $totalAmount, 
                'total_points' => $totalPoints, 
                'recorder_id' => $recorderId, 
            ]); 

            // อัปเดตยอดสะสมไปยัง kp_recycle_bank_members
            $recycleMember = KpRecycleBankMember::where('user_id', $userId)
                ->where('org_id_fk', $user->org_id_fk)
                ->first();

            if ($recycleMember) {
                $recycleMember->increment('total_points', $totalPoints);
                $recycleMember->increment('balance', $totalAmount);
            }

            foreach ($acceptedBottles as $item) { 
                KpPurchaseTransactionDetail::create([ 
                    'kp_purchase_trans_id' => $transaction->id, 
                    'org_id_fk' => $user->org_id_fk, 
                    'kp_recycle_item_id' => $item['kp_tbank_item_id'], 
                    'kp_tbank_items_pricepoint_id' => $item['kp_tbank_items_pricepoint_id'], 
                    'amount_in_units' => $item['amount_in_units'], 
                    'price_per_unit' => $item['price_per_unit'], 
                    'amount' => $item['amount'], 
                    'points' => $item['points'], 
                    'recorder_id' => $recorderId 
                ]); 
            } 

            DB::commit(); 
            return response()->json([ 
                'message' => 'Transaction recorded successfully.', 
                'transaction_id' => $transaction->id, 
                'total_amount' => $totalAmount, 
                'redirect_url' => route('keptkayas.purchase.receipt', $transaction->id), 
            ], 201); 

        } catch (\Exception $e) { 
            DB::rollBack(); 
            return response()->json([ 
                'error' => 'Failed to record transaction due to internal error.', 
                'details' => $e->getMessage(), 
            ], 500); 
        } 
    } 
}