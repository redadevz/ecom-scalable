<?php

namespace App\Http\Controllers\Shop\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Storefront customer authentication on the dedicated `customer` guard
 * (separate from the admin's craftable-pro guard). Hand-rolled — not Fortify —
 * to avoid clashing with Fortify's root routes.
 */
class AuthController extends Controller
{
    public function showLogin(): Response|RedirectResponse
    {
        return Auth::guard('customer')->check()
            ? redirect('/account')
            : Inertia::render('Shop/Auth/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('customer')->attempt(
            ['email' => $data['email'], 'password' => $data['password'], 'is_active' => true],
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        Auth::guard('customer')->user()->forceFill(['last_login_time' => now()])->saveQuietly();

        return redirect()->intended('/account');
    }

    public function showRegister(): Response|RedirectResponse
    {
        return Auth::guard('customer')->check()
            ? redirect('/account')
            : Inertia::render('Shop/Auth/Register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone'      => ['required', 'string', 'max:50', 'unique:customers,phone'],
            'password'   => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.unique' => 'An account with this email already exists.',
            'phone.unique' => 'An account with this phone number already exists.',
        ]);

        $store = Store::orderBy('id')->first();

        $customer = Customer::create([
            'city_id'              => $store?->city_id ?? City::orderBy('id')->value('id'),
            'created_at_store_id'  => $store?->id,
            'code'                 => $this->uniqueCode(),
            'first_name'           => $data['first_name'],
            'last_name'            => $data['last_name'],
            'email'                => $data['email'],
            'phone'                => $data['phone'],
            'password'             => $data['password'],
            'billing_address'      => '',
            'is_registered_online' => true,
            'is_active'            => true,
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect('/account')->with('message', 'Welcome — your account is ready.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function uniqueCode(): string
    {
        do {
            $code = 'WEB-' . strtoupper(Str::random(6));
        } while (Customer::where('code', $code)->exists());

        return $code;
    }
}
