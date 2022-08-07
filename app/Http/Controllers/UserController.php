<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::paginate(20), Response::HTTP_OK);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $user = User::query()->create(
            $request->only('first_name', 'last_name', 'email') +
            ['password' => Hash::make('1234'),]
        );
        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response()->json($user, Response::HTTP_ACCEPTED);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json('', Response::HTTP_NO_CONTENT);
    }
}
