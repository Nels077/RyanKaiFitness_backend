<?php

namespace App\Http\Controllers;

use App\Http\Contracts\UserRepositoryInterface;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userRepository->all();

        return response()->json($users);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function create(UserRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $user = $this->userRepository->create([
                'first_name' => $validatedData['name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully!',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param int $id
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UserUpdateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $isUpdated = $this->userRepository->update($validatedData, $id);

        if ($isUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully!',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update user!',
        ], 500);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $deleted = $this->userRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete user!',
        ], 500);
    }
}
