<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        $roleKey = $request->route('role') ?? $request->query('role');
        $roleMap = [
            'triage-nurse' => [
                'label' => 'Triage Nurse',
                'description' => 'Use the triage workflow to record vitals and start allocations.',
            ],
            'bed-manager' => [
                'label' => 'Bed Manager',
                'description' => 'Review beds, manage capacity, and handle placement decisions.',
            ],
            'doctor' => [
                'label' => 'Doctor',
                'description' => 'Open the doctor queue to review pending allocations.',
            ],
        ];

        return Inertia::render('User/Pages/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'roleContext' => $roleMap[$roleKey] ?? null,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
