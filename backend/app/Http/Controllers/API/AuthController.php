<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle Login and Issue Sanctum Token
     */
    public function login(Request $request)
    {
        // 1. Validation dyal les inputs mn Vue.js
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Check dyal l-User f la base de données
        $user = User::where('email', $request->email)->first();

        // 3. Vérification d password m-hachi
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        // 4. Génération de Token Sanctum m3a les permissions (Rôles)
        $token = $user->createToken('ocp-auth-token', [$user->role])->plainTextToken;

        // 5. Renvoi de data en format JSON exact l-Vue.js
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ], 200);
    }

    /**
     * Handle Logout and Revoke Token
     */
    public function logout(Request $request)
    {
        // Msa7 l-token li connecti bih l-user daba
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out.'
        ], 200);
    }
}