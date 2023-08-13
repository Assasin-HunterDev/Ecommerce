<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController manages user authentication-related actions.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Handle user login attempt.
     *
     * @param Request $request The incoming request containing user credentials.
     * @return Response|Application|ResponseFactory A response containing user data and token on successful login,
     *                                              or an error response on failed login attempt.
     */
    public function login(Request $request): Response|Application|ResponseFactory
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Email or password is incorrect!'
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();
        if (!$user->is_admin) {
            Auth::logout();

            return response([
                'message' => 'You don\'t have permission to authenticate as admin'
            ], 403);
        }
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    /**
     * Handle user logout.
     *
     * @return Response|Application|ResponseFactory A response indicating successful logout.
     */
    public function logout(): Response|Application|ResponseFactory
    {
        /** @var User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }


    /**
     * Get the authenticated user's data.
     *
     * @param Request $request The incoming request.
     * @return UserResource The user's resource representation.
     */
    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
