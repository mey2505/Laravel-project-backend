<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'Customer',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => $this->withRoles($user),
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user  = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => $this->withRoles($user),
            'token'   => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($this->withRoles($request->user()));
    }

    /**
     * Append role info to the user payload so the frontend can decide
     * whether to route them to the admin dashboard or the storefront.
     */
    protected function withRoles(User $user): array
    {
        $adminRoles = ['Super Admin', 'Admin', 'Manager', 'Staff'];

        $data = $user->toArray();
        $data['roles'] = $user->getRoleNames();
        $data['is_admin'] = $user->hasAnyRole($adminRoles);

        return $data;
    }
}
