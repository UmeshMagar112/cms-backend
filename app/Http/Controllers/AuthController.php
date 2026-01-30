<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // User / Company registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10',
            'address' =>'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,company,jobseeker',
        ]);

        // Determine verification status
        $isVerified = $request->role === 'company' ? false : true;

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'is_verified' => $isVerified,
        ]);

        // Assign role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        $message = $isVerified 
            ? 'User registered successfully' 
            : 'Your company registration is pending admin approval';

        return response()->json([
            'message' => $message,
            'user' => $user,
            'role' => $request->role,
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->with('roles')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $role = $user->roles->first();
        if (!$role) {
            return response()->json(['error' => 'Your account has no assigned role.'], 403);
        }

        // Block unverified company
        if ($role->name === 'company' && !$user->is_verified) {
            return response()->json([
                'error' => 'Your company account is not yet approved by admin.'
            ], 403);
        }

        // Generate Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'role' => $role->name,
            'token' => $token,
        ]);
    }


    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'auth_failed' => ['Authentication failed'],
        ]);
    }
}
