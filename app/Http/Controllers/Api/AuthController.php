<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $identifier = strtolower($credentials['identifier']);

        $user = User::query()
            ->where('role', $credentials['role'])
            ->where(function ($query) use ($identifier) {
                $grammar = $query->getQuery()->getGrammar();
                $emailColumn = $grammar->wrap('email');
                $usernameColumn = $grammar->wrap('username');

                $query->whereRaw(sprintf('LOWER(%s) = ?', $emailColumn), [$identifier])
                    ->orWhereRaw(sprintf('LOWER(%s) = ?', $usernameColumn), [$identifier]);
            })
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => __('Email, username, atau kata sandi yang Anda masukkan belum sesuai.'),
            ]);
        }

        Auth::login($user, $credentials['remember'] ?? false);
        $request->session()->regenerate();

        return response()->json([
            'user' => $this->transformUser($user),
        ]);
    }

    /**
     * Destroy the authenticated session.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    /**
     * Retrieve the currently authenticated user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof Authenticatable) {
            return response()->json([
                'message' => __('Sesi pengguna tidak ditemukan.'),
            ], 401);
        }

        return response()->json([
            'user' => $this->transformUser($user),
        ]);
    }

    /**
     * Transform a user model into the structure expected by the SPA.
     */
    protected function transformUser(User $user): array
    {
        return [
            'id' => $user->getKey(),
            'name' => $user->name,
            'shortName' => $user->short_name ?: $user->name,
            'role' => $user->role ?: 'teacher',
            'avatar' => $user->avatar_url,
            'organization' => $user->organization_name ?: 'Terminal Pintar',
            'contact' => [
                'email' => $user->email,
            ],
        ];
    }
}
