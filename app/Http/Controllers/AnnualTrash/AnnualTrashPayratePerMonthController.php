<?php 

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use App\Models\KeptKaya\KpUserGroup; 
use App\Models\Admin\BudgetYear; 
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth; 
use Illuminate\Support\Facades\Auth;

class AnnualTrashPayratePerMonthController extends Controller 
{ 
    public function index() 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        $payrates = AnnualTrashPayratePerMonth::with(['kp_usergroup', 'budgetyear']) 
            ->where('org_id_fk', $orgId)
            ->where('deleted', 0) 
            ->orderBy('budgetyear_idfk', 'desc') 
            ->orderBy('kp_usergroup_idfk', 'asc') 
            ->paginate(10); 

        return view('annual_trash.rates.index', compact('payrates'));
    } 

    public function create() 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        $usergroups = KpUserGroup::where('status', 'active')->get(); 
        $budgetYears = BudgetYear::where('org_id_fk', $orgId)->where('status', 'active')->orderBy('id', 'desc')->get(); 

        return view('annual_trash.rates.create', compact('usergroups', 'budgetYears'));
    } 

    public function store(Request $request) 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        $request->validate([ 
            'kp_usergroup_idfk' => 'required',
            'budgetyear_idfk'   => 'required|integer',
            'payrate_permonth'  => 'required|numeric', 
        ]); 

        $budgetYear = BudgetYear::where('org_id_fk', $orgId)->findOrFail($request->budgetyear_idfk);

        $existingRecord = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
            ->where('kp_usergroup_idfk', $request->kp_usergroup_idfk) 
            ->where('budgetyear_idfk', $request->budgetyear_idfk) 
            ->where('deleted', 0)
            ->first(); 

        if ($existingRecord) { 
            return redirect()->route('keptkayas.wbin_payrate_per_months.index') 
                ->with('error', 'มีการกำหนดอัตราค่าบริการของกลุ่มผู้ใช้งานและปีงบประมาณนี้อยู่แล้ว'); 
        } 

        try { 
            AnnualTrashPayratePerMonth::create([ 
                'org_id_fk'         => $orgId,
                'kp_usergroup_idfk' => $request->get('kp_usergroup_idfk'), 
                'budgetyear_idfk'   => $budgetYear->id, 
                'payrate_permonth'  => $request->get('payrate_permonth'), 
                'status'            => $request->get('status', 'active'), 
                'vat'               => $request->get('vat', 0), 
            ]); 

            return redirect()->route('keptkayas.wbin_payrate_per_months.index')
                ->with('success', 'เพิ่มอัตราค่าบริการสำเร็จแล้ว.'); 
        } catch (\Exception $e) { 
            return redirect()->route('keptkayas.wbin_payrate_per_months.index')
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()); 
        } 
    } 

    public function edit($id) 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        $payrate = AnnualTrashPayratePerMonth::with(['kp_usergroup', 'budgetyear'])
            ->where('org_id_fk', $orgId)
            ->where('deleted', 0)
            ->findOrFail($id); 

        $usergroups = KpUserGroup::where('status', 'active')->get(); 
        $budgetYears = BudgetYear::where('org_id_fk', $orgId)->where('status', 'active')->orderBy('id', 'desc')->get();

        return view('annual_trash.rates.edit', compact('payrate', 'usergroups', 'budgetYears'));
    } 

    public function update(Request $request, $id) 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        $request->validate([ 
            'payrate_permonth' => 'required|numeric',
        ]); 

        $payrate = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)->where('deleted', 0)->findOrFail($id); 
        $budgetYearId = $request->input('budgetyear_idfk', $payrate->budgetyear_idfk);
        $budgetYear = BudgetYear::where('org_id_fk', $orgId)->findOrFail($budgetYearId);

        try { 
            $payrate->update([ 
                'kp_usergroup_idfk' => $request->get('kp_usergroup_idfk', $payrate->kp_usergroup_idfk),
                'budgetyear_idfk'   => $budgetYear->id,
                'payrate_permonth'  => $request->get('payrate_permonth'),
                'status'            => $request->get('status', $payrate->status),
                'vat'               => $request->get('vat', $payrate->vat),
            ]); 

            return redirect()->route('keptkayas.wbin_payrate_per_months.index')
                ->with('success', 'อัปเดตอัตราค่าบริการสำเร็จแล้ว.'); 
        } catch (\Exception $e) { 
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage());
        } 
    } 

    public function destroy($id) 
    { 
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        try { 
            $payrate = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)->where('deleted', 0)->findOrFail($id); 
            $payrate->update(['deleted' => 1, 'status' => 'inactive']); 

            return redirect()->route('keptkayas.wbin_payrate_per_months.index')
                ->with('success', 'ลบอัตราค่าบริการสำเร็จแล้ว.'); 
        } catch (\Exception $e) { 
            return redirect()->route('keptkayas.wbin_payrate_per_months.index')
                ->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        } 
    } 
}