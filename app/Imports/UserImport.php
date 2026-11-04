<?php

namespace App\Imports;

use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\Rule;

class UserImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        $orgId = (int) Auth::user()->org_id_fk;

        // 1. สร้าง Master User ลงในตารางหลัก (users)
        $user = new User([
            'org_id_fk'     => $orgId,
            'username'      => trim((string) $row['username']),
            'prefix'        => trim((string) ($row['prefix'] ?? 'นาย')),
            'firstname'     => trim((string) ($row['firstname'] ?? '')),
            'lastname'      => trim((string) ($row['lastname'] ?? '')),
            'email'         => !empty($row['email']) ? trim((string) $row['email']) : null,
            'password'      => Hash::make((string) $row['password']),
            'id_card'       => isset($row['id_card']) ? (string) $row['id_card'] : null,
            'line_id'       => isset($row['line_id']) ? (string) $row['line_id'] : '0',
            'line_user_id'  => isset($row['line_user_id']) ? (string) $row['line_user_id'] : '0',
            'image'         => $row['image'] ?? null,
            'phone'         => isset($row['phone']) ? (string) $row['phone'] : null,
            'age'           => isset($row['age']) ? (int) $row['age'] : null,
            'weight'        => isset($row['weight']) ? (float) $row['weight'] : null,
            'height'        => isset($row['height']) ? (float) $row['height'] : null,
            'gender'        => !empty($row['gender']) ? strtolower(trim((string) $row['gender'])) : null,
            'address'       => isset($row['address']) ? (string) $row['address'] : null,
            'zone_id'       => isset($row['zone_id']) ? (int) $row['zone_id'] : null,
            'subzone_id'    => isset($row['subzone_id']) ? (int) $row['subzone_id'] : null,
            'tambon_code'   => isset($row['tambon_code']) ? (int) $row['tambon_code'] : null,
            'district_code' => isset($row['district_code']) ? (int) $row['district_code'] : null,
            'province_code' => isset($row['province_code']) ? (int) $row['province_code'] : null,
            'status'        => !empty($row['status']) ? strtolower(trim((string) $row['status'])) : 'active',
        ]);
        $user->save();

        // 2. กำหนด Role พื้นฐาน 'User'
        $user->assignRole('User');

        // 3. สร้าง Preference เริ่มต้น และหากระบุเปิดบริการในไฟล์ Excel ให้ลงทะเบียนเข้าโมดูลผ่าน UserService มาตรฐานเดียวกัน
        KpUserWastePreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'org_id_fk'            => $orgId,
                'is_annual_collection' => !empty($row['is_annual_collection']) ? 1 : 0,
                'is_recycle_bank'      => (!empty($row['is_recycle_bank']) || !empty($row['is_waste_bank'])) ? 1 : 0,
                'address'              => $user->address,
                'zone_id'              => $user->zone_id,
                'subzone_id'           => $user->subzone_id,
                'tambon_code'          => $user->tambon_code,
                'district_code'        => $user->district_code,
                'province_code'        => $user->province_code,
            ]
        );

        $userService = app(UserService::class);
        if (!empty($row['is_recycle_bank']) || !empty($row['is_waste_bank'])) {
            $userService->registerToRecycleBank($user);
        }
        if (!empty($row['is_annual_collection'])) {
            $userService->registerToAnnualTrash($user);
        }

        return $user;
    }

    public function rules(): array
    {
        $orgId = (int) Auth::user()->org_id_fk;

        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:6'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'id_card' => ['nullable', 'string', 'max:13', Rule::unique('users', 'id_card')],
            'phone' => ['nullable', 'string', 'max:20'],
            'age' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'zone_id' => ['nullable', 'integer', Rule::exists('zones', 'id')->where('org_id_fk', $orgId)],
            'subzone_id' => ['nullable', 'integer', Rule::exists('subzones', 'id')->where('org_id_fk', $orgId)],
            'tambon_code' => ['nullable', 'integer'],
            'district_code' => ['nullable', 'integer'],
            'province_code' => ['nullable', 'integer'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'is_annual_collection' => ['nullable', 'boolean'],
            'is_recycle_bank' => ['nullable', 'boolean'],
            'is_waste_bank' => ['nullable', 'boolean'],
        ];
    }
}
