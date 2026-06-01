<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\ApiResponser;

class ApiAuthController extends Controller
{
    use ApiResponser;

    /**
     * Authenticate Admin and generate Sanctum token.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            return $this->errorResponse('Invalid login credentials', 401);
        }

        $user = Auth::user();

        // Optional: Ensure only admins can login to the mobile app
        if ($user->role !== 'admin') {
            Auth::logout();
            return $this->errorResponse('Unauthorized access. Admin privileges required.', 403);
        }

        // Revoke older tokens if you want only one active session per admin
        // $user->tokens()->delete();

        // Create the token. We name it 'admin-mobile-app'
        $token = $user->createToken('admin-mobile-app')->plainTextToken;

        return $this->successResponse([
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Revoke current token (Logout)
     */
    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();
        $token->delete();
        
        return $this->successResponse(null, 'Successfully logged out');
    }
}
