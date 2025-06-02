<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        // $organization = app('currentOrganization');

        // if (!$organization) {
        //     return response()->json(['message' => 'Organization context required'], 400);
        // }

        $user = User::where('email', $credentials['email'])
            ->first();

        if (!$user || !Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Delete old tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'org_domain' => $user->currentOrganization->domain ?? null,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->tokens()->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'organizationName' => 'required|string|max:255',
            'organizationDomain' => 'required|string|max:255|unique:organizations,slug',
            'adminName' => 'required|string|max:255',
            'adminEmail' => 'required|email|unique:users,email',
            'adminPassword' => 'required|string|min:8|same:adminPasswordConfirmation',
            'adminPasswordConfirmation' => 'required|string|min:8',
        ]);

        // Create organization
        $organization = \App\Models\Organization::create([
            'name' => $validated['organizationName'],
            // 'slug' => $validated['organizationDomain'],
            'domain' => $validated['organizationDomain'],
        ]);

        // Create admin user
        $user = \App\Models\User::create([
            'name' => $validated['adminName'],
            'email' => $validated['adminEmail'],
            'password' => bcrypt($validated['adminPassword']),
            'role' => 'admin',
            'current_organization_id' => $organization->id,
        ]);

        // Attach user to organization
        $user->organizations()->attach($organization->id, [
            'is_owner' => true,
        ]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'org_domain' => $organization->domain,
                'token_type' => 'Bearer',
            ]
        ]);
    }
}
