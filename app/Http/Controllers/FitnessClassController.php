<?php

namespace App\Http\Controllers;

use App\Http\Contracts\FitnessClassRepositoryInterface;
use App\Http\Requests\FitnessClassCreateRequest;
use App\Http\Requests\FitnessClassUpdateRequest;
use App\Models\FitnessClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FitnessClassController extends Controller
{
    /**
     * @param FitnessClassRepositoryInterface $fitnessClassRepository
     */
    public function __construct(protected FitnessClassRepositoryInterface $fitnessClassRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $fitnessClasses = $this->fitnessClassRepository->all();

        return response()->json($fitnessClasses);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $fitnessClass = $this->fitnessClassRepository->find($id);
        if (!$fitnessClass) {
            return response()->json(['message' => 'Fitness class not found'], 404);
        }
        return response()->json($fitnessClass);
    }

    public function create(FitnessClassCreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $fitnessClass = $this->fitnessClassRepository->create([
                'title' => $validatedData['title'],
                'subtitle' => $validatedData['subtitle'],
                'duration' => $validatedData['duration'],
                'working_days' => $validatedData['working_days'],
                'price' => $validatedData['price'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Fitness Class created successfully!',
                'data' => $fitnessClass,
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
     * @param int $fitnessClassId
     * @return JsonResponse
     */
    public function bookFitnessClass(int $fitnessClassId): JsonResponse
    {
        $user = auth()->user();

        $fitnessClass = FitnessClass::find($fitnessClassId);

        if (!$fitnessClass) {
            return response()->json(['status' => 'error', 'message' => 'Fitness class not found'], 404);
        }

        $user->fitnessClasses()->syncWithoutDetaching($fitnessClassId);

        return response()->json([
            'status' => 'success',
            'message' => 'Fitness class booked successfully!',
            'data' => $user->load('fitnessClasses'),
        ]);
    }


    /**
     * @param int $id
     * @param FitnessClassUpdateRequest $request
     * @return JsonResponse
     */
    public function update(int $id, FitnessClassUpdateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $fitnessClass = $this->fitnessClassRepository->find($id);
        if (!$fitnessClass) {
            return response()->json(['message' => 'Fitness Class not found'], 404);
        }

        $isUpdated = $this->fitnessClassRepository->update($validatedData, $id);
        if ($isUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Fitness Class updated successfully!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update Fitness Class!',
        ], 500);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $fitnessClass = $this->fitnessClassRepository->find($id);
        if (!$fitnessClass) {
            return response()->json(['message' => 'Fitness Class not found'], 404);
        }

        $deleted = $this->fitnessClassRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Fitness Class deleted successfully!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete Fitness Class!',
        ], 500);
    }
}
