<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $user = $this->userRepository->create([
                'first_name' => $validatedData['first_name'],
                'email' => $validatedData['email'],
                'last_name' => $validatedData['last_name'],
                'password' => Hash::make($validatedData['password']),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully!',
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
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $this->userRepository->login(['email' => $request->input('email')]);

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $token = $user->createToken('token');

            return response()->json(['user' => $user, 'token' => $token->plainTextToken]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 404);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully!',
        ]);
    }
}
