<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $user = User::query()->create(
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            ['password' => Hash::make('1234'),]
        );
        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response()->json(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * @param UpdateInfoRequest $request
     * @return JsonResponse
     */
    public function updateInfo(UpdateInfoRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email'));
        return response()->json(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->get('password')),
        ]);
        return response()->json(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
