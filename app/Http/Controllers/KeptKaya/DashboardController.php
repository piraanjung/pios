<?php 

namespace App\Http\Controllers\KeptKaya; 

use App\Http\Controllers\Controller; 
use App\Models\KeptKaya\KpPurchaseTransactionDetail; 
use App\Models\KeptKaya\KpPurchaseTransaction; 
use App\Models\Keptkaya\KpRecycleBankMember; // เรียกใช้ Model ใหม่
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request; 

class DashboardController extends Controller 
{ 
    public function index(Request $request, $keptkayatype = '') 
    { 
        $request->session()->forget('keptkayatype'); 
        $orgId = Auth::user()->org_id_fk; 

        $schoolStats = $this->getCarbonSummary($orgId); 

        // 1. อันดับสมาชิกที่ลดคาร์บอนสูงสุด (เปลี่ยนมาใช้ kpRecycleBankMember)
        $topStudents = User::join('kp_purchase_transactions', 'users.id', '=', 'kp_purchase_transactions.user_id') 
            ->select('users.firstname', 'users.lastname', DB::raw('SUM(kp_purchase_transactions.total_carbon_saved) as total_carbon')) 
            ->where('kp_purchase_transactions.org_id_fk', $orgId) 
            ->where('kp_purchase_transactions.deleted', 0) 
            ->whereHas('kpRecycleBankMember', function($q) use ($orgId) { 
                $q->where('org_id_fk', $orgId); 
            }) 
            ->groupBy('users.id', 'users.firstname', 'users.lastname') 
            ->orderByDesc('total_carbon') 
            ->take(5) 
            ->get(); 

        // 2. แนวโน้มการลดคาร์บอนรายเดือน
        $monthlyTrend = KpPurchaseTransaction::select( 
            DB::raw("DATE_FORMAT(transaction_date, '%Y-%m') as month"), 
            DB::raw('SUM(total_carbon_saved) as total_carbon') 
        ) 
        ->where('org_id_fk', $orgId) 
        ->where('deleted', 0) 
        ->where('transaction_date', '>=', now()->subMonths(6)) 
        ->groupBy('month') 
        ->orderBy('month') 
        ->get(); 

        // 3. สถิติเศรษฐกิจ (รวมเงินและแต้ม)
        $economicStats = [ 
            'total_money'  => KpPurchaseTransaction::where('org_id_fk', $orgId)->where('deleted', 0)->sum('total_amount'), 
            'total_points' => KpPurchaseTransaction::where('org_id_fk', $orgId)->where('deleted', 0)->sum('total_points') 
        ]; 

        // ถ้ายังไม่มีการทำรายการ ให้ดึงจากยอดสะสมในตาราง kp_recycle_bank_members แทน KpBankAccount
        if ($economicStats['total_money'] == 0 && $economicStats['total_points'] == 0) { 
            $economicStats = [ 
                'total_money'  => KpRecycleBankMember::where('org_id_fk', $orgId)->sum('balance'), 
                'total_points' => KpRecycleBankMember::where('org_id_fk', $orgId)->sum('total_points'), 
            ]; 
        } 

        // 4. กิจกรรมล่าสุด (เปลี่ยนมาโหลด user ตรงๆ ไม่ผ่าน preference)
        $recentActivities = KpPurchaseTransaction::where('org_id_fk', $orgId) 
            ->where('deleted', 0) 
            ->with('user') 
            ->latest('created_at') 
            ->take(5) 
            ->get(); 

        $chartLabels = $schoolStats->pluck('material_name'); 
        $chartData = $schoolStats->pluck('total_weight'); 
        $carbonSavedChartData = $schoolStats->pluck('carbon_saved'); 

        // 5. จำนวนสมาชิกธนาคาtotal_weightรขยะทั้งหมดใน อปท. นี้
        $totalMembers = User::where('org_id_fk', $orgId) 
            ->whereHas('kpRecycleBankMember', function($q) use ($orgId){ 
                $q->where('org_id_fk', $orgId); 
            }) 
            ->count(); 

        $request->session()->put('keptkayatype', $keptkayatype); 

        return view('keptkayas.dashboard_recycle', compact( 
            'schoolStats', 
            'chartLabels', 
            'chartData', 
            'carbonSavedChartData',
            'topStudents', 
            'monthlyTrend', 
            'economicStats', 
            'recentActivities', 
            'totalMembers' 
        )); 
    } 

    public function getCarbonSummary($orgId = null, $userId = null) 
    { 
        $orgId = $orgId ?? Auth::user()->org_id_fk; 

        $query = KpPurchaseTransactionDetail::query() 
            ->join('kp_purchase_transactions', 'kp_purchase_transactions_details.kp_purchase_trans_id', '=', 'kp_purchase_transactions.id') 
            ->join('kp_tbank_items', 'kp_purchase_transactions_details.kp_recycle_item_id', '=', 'kp_tbank_items.id') 
            ->where('kp_purchase_transactions.org_id_fk', $orgId) 
            ->where('kp_purchase_transactions.deleted', 0) 
            ->select( 
                'kp_tbank_items.id', 
                'kp_tbank_items.kp_itemsname as material_name', 
                DB::raw('SUM(kp_purchase_transactions_details.carbon_saved) as total_carbon'), 
                DB::raw('SUM(kp_purchase_transactions_details.amount_in_units) as total_weight') 
            ); 

        if ($userId) { 
            $query->where('kp_purchase_transactions.user_id', $userId); 
        } 

        return $query->groupBy('kp_tbank_items.id', 'kp_tbank_items.kp_itemsname') 
            ->orderByDesc('total_carbon') 
            ->get(); 
    } 
}