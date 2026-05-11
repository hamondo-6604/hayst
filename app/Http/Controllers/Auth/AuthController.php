<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Handle AJAX Login
     */
    public function login_post(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Check status
            if ($user->status === 'blocked') {
                Auth::logout();
                return response()->json(['success' => false, 'message' => 'Your account is blocked.'], 403);
            }

            // Determine redirect
            $redirect = route('landing.home');
            
            if ($user->isAdmin()) {
                $redirect = route('admin.dashboard');
            } elseif ($user->isDriver()) {
                $redirect = route('driver.dashboard');
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Welcome back, ' . $user->name,
                'redirect' => $redirect
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Invalid email or password.'
        ], 401);
    }

    /**
     * Handle AJAX Registration
     */
    public function register_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'phone'            => 'nullable|string|max:20',
            'discount_type_id' => 'required|exists:discount_types,id',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get the 'customer' user type ID for the foreign key
        $customerType = UserType::where('name', 'customer')->first();

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'password'         => Hash::make($request->password),
            'role'             => 'customer', // Enum value
            'user_type_id'     => $customerType?->id,
            'discount_type_id' => $request->discount_type_id,
            'status'           => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message'  => 'Registration successful! You can now sign in.',
        ]);
    }

    /**
     * Handle Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing.home');
    }

    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Create new user
                $customerType = UserType::where('name', 'customer')->first();
                
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // Random password
                    'role' => 'customer',
                    'user_type_id' => $customerType?->id,
                    'status' => 'active',
                ]);
                
                Auth::login($user);
            }

            // Determine redirect
            $redirect = route('landing.home');
            
            if ($user->isAdmin()) {
                $redirect = route('admin.dashboard');
            } elseif ($user->isDriver()) {
                $redirect = route('driver.dashboard');
            }

            return redirect($redirect);

        } catch (\Exception $e) {
            return redirect()->route('landing.home', ['open_login' => 1])
                ->with('error', 'Google authentication failed. Please try again.');
        }
    }
}