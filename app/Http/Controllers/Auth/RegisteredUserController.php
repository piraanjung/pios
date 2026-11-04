<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserProfile;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_card' => ['nullable', 'string', 'max:13', 'unique:users,id_card'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'username'      => $request->username ?? $request->name ?? $request->email,
            'prefix'        => $request->prefix ?? 'นาย',
            'firstname'     => $request->firstname ?? $request->name ?? '',
            'lastname'      => $request->lastname ?? '',
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'id_card'       => $request->id_card,
            'phone'         => $request->phone,
            'gender'        => $request->gender ?? 'm',
            'address'       => $request->address,
            'org_id_fk'     => $request->organization_id ?? $request->org_id_fk,
            'zone_id'       => $request->zone_id,
            'subzone_id'    => $request->subzone_id,
            'tambon_code'   => $request->tambon_code,
            'district_code' => $request->district_code,
            'province_code' => $request->province_code,
            'status'        => 'active',
        ]);
        $user->assignRole('User');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
