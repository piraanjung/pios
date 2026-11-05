<?php

namespace App\Features\IdentityAndOnboarding\OnboardCitizen;

use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OnboardCitizenController extends Controller
{
    public function __invoke(OnboardCitizenRequest $request, OnboardCitizenAction $action): JsonResponse|RedirectResponse
    {
        $dto = OnboardCitizenDTO::fromRequest($request);
        $result = $action->execute($dto);

        if ($request->wantsJson()) {
            return ApiResponse::created(
                data: [
                    'user_id'       => $result['user']->id,
                    'username'      => $result['user']->username,
                    'account_no'    => $result['bank_account']?->account_no,
                    'member_number' => $result['bank_account']?->member_number,
                ],
                message: $result['message']
            );
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
