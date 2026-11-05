<?php

namespace App\Features\IdentityAndOnboarding\OnboardCitizen;

class OnboardCitizenDTO
{
    public function __construct(
        public readonly int $orgId,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly ?string $prefix = null,
        public readonly ?string $idCard = null,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly ?string $address = null,
        public readonly ?int $zoneId = null,
        public readonly ?int $subzoneId = null,
        public readonly ?string $lineUserId = null,
        public readonly bool $enableRecycleBank = true,
        public readonly bool $enableWelfare = false,
        public readonly bool $enableFoodWaste = false,
        public readonly bool $enableTabwater = false,
        public readonly ?string $meterNumber = null,
    ) {}

    public static function fromRequest(OnboardCitizenRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            orgId: (int) ($validated['org_id_fk'] ?? auth()->user()?->org_id_fk ?? 1),
            firstname: $validated['firstname'],
            lastname: $validated['lastname'],
            prefix: $validated['prefix'] ?? null,
            idCard: $validated['id_card'] ?? null,
            phone: $validated['phone'] ?? null,
            email: $validated['email'] ?? null,
            address: $validated['address'] ?? null,
            zoneId: isset($validated['zone_id']) ? (int) $validated['zone_id'] : null,
            subzoneId: isset($validated['subzone_id']) ? (int) $validated['subzone_id'] : null,
            lineUserId: $validated['line_user_id'] ?? null,
            enableRecycleBank: (bool) ($validated['enable_recycle_bank'] ?? true),
            enableWelfare: (bool) ($validated['enable_welfare'] ?? false),
            enableFoodWaste: (bool) ($validated['enable_food_waste'] ?? false),
            enableTabwater: (bool) ($validated['enable_tabwater'] ?? false),
            meterNumber: $validated['meter_number'] ?? null,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            orgId: (int) ($data['org_id_fk'] ?? 1),
            firstname: $data['firstname'],
            lastname: $data['lastname'],
            prefix: $data['prefix'] ?? null,
            idCard: $data['id_card'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            address: $data['address'] ?? null,
            zoneId: isset($data['zone_id']) ? (int) $data['zone_id'] : null,
            subzoneId: isset($data['subzone_id']) ? (int) $data['subzone_id'] : null,
            lineUserId: $data['line_user_id'] ?? null,
            enableRecycleBank: (bool) ($data['enable_recycle_bank'] ?? true),
            enableWelfare: (bool) ($data['enable_welfare'] ?? false),
            enableFoodWaste: (bool) ($data['enable_food_waste'] ?? false),
            enableTabwater: (bool) ($data['enable_tabwater'] ?? false),
            meterNumber: $data['meter_number'] ?? null,
        );
    }
}
