<?php

namespace App\Shared\Domain\Welfare;

use Carbon\Carbon;
use DateTimeInterface;

class MembershipDurationRule
{
    public const DEFAULT_REQUIRED_DAYS = 180;

    /**
     * Determine whether membership duration satisfies the welfare threshold.
     */
    public function isSatisfied(
        DateTimeInterface|string $joinedDate,
        DateTimeInterface|string|null $asOfDate = null,
        int $requiredDays = self::DEFAULT_REQUIRED_DAYS
    ): array {
        $joined = Carbon::parse($joinedDate);
        $asOf = $asOfDate ? Carbon::parse($asOfDate) : Carbon::now();

        $membershipDays = $joined->diffInDays($asOf);
        $eligible = $membershipDays >= $requiredDays;

        return [
            'eligible'        => $eligible,
            'membership_days' => $membershipDays,
            'required_days'   => $requiredDays,
            'remaining_days'  => $eligible ? 0 : ($requiredDays - $membershipDays),
            'reason'          => $eligible
                ? 'อายุการเป็นสมาชิกครบตามเกณฑ์ที่กำหนด'
                : "อายุการเป็นสมาชิกไม่ครบกำหนด (ขาดอีก " . ($requiredDays - $membershipDays) . " วัน)",
        ];
    }
}
