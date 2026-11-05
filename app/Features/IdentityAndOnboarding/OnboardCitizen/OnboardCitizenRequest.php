<?php

namespace App\Features\IdentityAndOnboarding\OnboardCitizen;

use Illuminate\Foundation\Http\FormRequest;

class OnboardCitizenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'org_id_fk'           => 'nullable|integer',
            'prefix'              => 'nullable|string|max:30',
            'firstname'           => 'required|string|max:100',
            'lastname'            => 'required|string|max:100',
            'id_card'             => 'nullable|string|max:20',
            'phone'               => 'nullable|string|max:30',
            'email'               => 'nullable|email|max:150',
            'address'             => 'nullable|string|max:255',
            'zone_id'             => 'nullable|integer',
            'subzone_id'          => 'nullable|integer',
            'line_user_id'        => 'nullable|string|max:100',
            'enable_recycle_bank' => 'nullable|boolean',
            'enable_welfare'      => 'nullable|boolean',
            'enable_food_waste'   => 'nullable|boolean',
            'enable_tabwater'     => 'nullable|boolean',
            'meter_number'        => 'nullable|string|max:50',
        ];
    }
}
