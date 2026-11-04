<?php
namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnualTrashAdminController extends Controller
{
    public function index(Request $request)
    {
        $orgId = function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk;
        $currentFiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        $fiscalYear = (int) $request->input('fiscal_year', $currentFiscalYear);
        $status = $request->input('status', 'all');
        $search = trim((string) $request->input('search', ''));

        if ($fiscalYear < 2400 || $fiscalYear > 2700) {
            $fiscalYear = $currentFiscalYear;
        }

        $paymentsQuery = AnnualTrashSubscription::query()
            ->where('org_id_fk', $orgId)
            ->where('fiscal_year', $fiscalYear)
            ->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId))
            ->with([
                'user',
                'AnnualTrash' => fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId)),
                'payments',
            ]);

        if ($search !== '') {
            $paymentsQuery->where(function ($query) use ($search, $orgId) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('AnnualTrash', function ($binQuery) use ($search, $orgId) {
                    $binQuery->where('bin_code', 'like', "%{$search}%")
                        ->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId));
                });
            });
        }

        if ($status === 'paid') {
            $paymentsQuery->where(function ($query) {
                $query->where('status', 'paid')
                    ->orWhereColumn('total_paid_amt', '>=', 'annual_fee');
            });
        } elseif ($status === 'partially_paid') {
            $paymentsQuery->where('status', 'partially_paid');
        } elseif ($status === 'pending') {
            $paymentsQuery->whereIn('status', ['active', 'pending']);
        } else {
            $status = 'all';
        }

        $yearQuery = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->where('fiscal_year', $fiscalYear);

        $summary = [
            'accounts' => (clone $yearQuery)->count(),
            'paid_accounts' => (clone $yearQuery)->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId))
                ->where(function ($query) {
                    $query->where('status', 'paid')
                        ->orWhereColumn('total_paid_amt', '>=', 'annual_fee');
                })->count(),
            'partial_accounts' => (clone $yearQuery)->where('status', 'partially_paid')->count(),
            'amount_due' => (float) (clone $yearQuery)->sum('annual_fee'),
            'amount_paid' => (float) (clone $yearQuery)->sum('total_paid_amt'),
            'outstanding' => (float) (clone $yearQuery)
                ->selectRaw('COALESCE(SUM(GREATEST(annual_fee - total_paid_amt, 0)), 0) as outstanding')
                ->value('outstanding'),
            'pending' => (clone $yearQuery)->whereHas('user', fn($query) => $query->where('org_id_fk', $orgId))
                ->whereIn('status', ['active', 'pending', 'partially_paid'])->count(),
        ];

        $years = AnnualTrashSubscription::where('org_id_fk', $orgId)
            ->select('fiscal_year')->distinct()->orderByDesc('fiscal_year')
            ->pluck('fiscal_year')->map(fn($value) => (int) $value)
            ->prepend($currentFiscalYear)->prepend($fiscalYear)->unique()->sortDesc()->values();

        $subscriptions = $paymentsQuery->orderBy('id')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('annual_trash.index', compact('subscriptions', 'summary', 'years', 'fiscalYear', 'status', 'search'));
    }
}