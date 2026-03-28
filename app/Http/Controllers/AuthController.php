<?php

namespace App\Http\Controllers;

use App\Models\FieldOfficer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Already logged in? Redirect to appropriate dashboard
        if (session()->has('officer_id')) {
            return redirect()->route('officer.dashboard');
        }
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        $officers = FieldOfficer::orderBy('name')->get();
        return view('auth.login', compact('officers'));
    }

    /**
     * Officer login: name must exist in field_officers + valid email format.
     * We don't store/check the email against a DB column — it's their own email.
     */
    public function officerLogin(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|exists:field_officers,id',
            'email'       => 'required|email',
        ], [
            'officer_id.required' => 'Please select your name.',
            'officer_id.exists'   => 'Selected officer is not recognised.',
            'email.required'      => 'Please enter your email address.',
            'email.email'         => 'Please enter a valid email address.',
        ]);

        $officer = FieldOfficer::findOrFail($request->officer_id);

        // Store officer info in session
        session([
            'officer_id'    => $officer->id,
            'officer_name'  => $officer->name,
            'officer_email' => $request->email,
        ]);

        return redirect()->route('officer.dashboard')
                         ->with('success', 'Welcome back, ' . $officer->name . '!');
    }

    /**
     * Admin login: email + password against users table.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'admin_email'    => 'required|email',
            'admin_password' => 'required|string',
        ]);

        $user = User::where('email', $request->admin_email)
                    ->where('is_admin', true)
                    ->first();

        if (! $user || ! Hash::check($request->admin_password, $user->password)) {
            return back()
                ->withInput(['active_tab' => 'admin'])
                ->withErrors(['admin_password' => 'Invalid admin credentials.']);
        }

        session([
            'admin_id'   => $user->id,
            'admin_name' => $user->name,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
