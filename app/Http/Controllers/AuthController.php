<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        return Auth::attempt($request->only('email', 'password')) ?
            $this->getAccessTokenForLoggedInUser(Auth::user()) :
            $this->userNotFoundResponse();
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email') +
            ['password' => Hash::make($request->get('password'))]
        );
        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * @param Authenticatable $user
     * @return JsonResponse
     */
    protected function getAccessTokenForLoggedInUser(Authenticatable $user): JsonResponse
    {
        $token = $user->createToken('admin')->accessToken;
        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    protected function userNotFoundResponse(): JsonResponse
    {
        return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }
}
