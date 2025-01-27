<?php

namespace App\Http\Controllers;

use App\Http\Contracts\MembershipRepositoryInterface;
use App\Http\Requests\MembershipBenefitCreateRequest;
use App\Http\Requests\MembershipBenefitUpdateRequest;
use App\Http\Requests\MembershipCreateRequest;
use App\Http\Requests\MembershipUpdateRequest;
use App\Models\Membership;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    /**
     * @param MembershipRepositoryInterface $membershipRepository
     */
    public function __construct(protected MembershipRepositoryInterface $membershipRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $memberships = $this->membershipRepository->all();

        return response()->json($memberships);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $membership = $this->membershipRepository->find($id);
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }
        return response()->json($membership);
    }

    /**
     * @param MembershipCreateRequest $request
     * @return JsonResponse
     */
    public function create(MembershipCreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $membership = $this->membershipRepository->create([
                'title' => $validatedData['title'],
                'price' => $validatedData['price'],
                'description' => $validatedData['description'],
                'subtitle' => $validatedData['subtitle']
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Membership created successfully!',
                'data' => $membership,
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
     * @param int $membershipId
     * @param MembershipBenefitCreateRequest $request
     * @return JsonResponse
     */
    public function addBenefits(int $membershipId, MembershipBenefitCreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $membership = $this->membershipRepository->find($membershipId);

        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        foreach ($validatedData['benefits'] as $benefit) {
            $membership->benefits()->create(['membership_id' => $membershipId, 'text' => $benefit]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Benefits added successfully!',
            'data' => $membership->load('benefits'),
        ]);
    }

    /**
     * Update a specific benefit for a membership.
     *
     * @param int $membershipId
     * @param int $benefitId
     * @param MembershipBenefitUpdateRequest $request
     * @return JsonResponse
     */
    public function updateBenefit(int $membershipId, int $benefitId, MembershipBenefitUpdateRequest $request): JsonResponse
    {
        $membership = Membership::find($membershipId);

        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        $benefit = $membership->benefits()->find($benefitId);

        if (!$benefit) {
            return response()->json(['message' => 'Benefit not found'], 404);
        }

        $benefit->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Benefit updated successfully!',
            'data' => $benefit,
        ]);
    }

    /**
     * @param int $membershipId
     * @return JsonResponse
     */
    public function bookMembership(int $membershipId): JsonResponse
    {
        $user = auth()->user();
        $membership = $this->membershipRepository->find($membershipId);

        if (!$membership) {
            return response()->json(['status' => 'error', 'message' => 'Membership not found'], 404);
        }

        $existingMembership = $user->memberships()
            ->where('membership_id', $membershipId)
            ->wherePivot('active_until', '>', now())
            ->exists();

        if ($existingMembership) {
            return response()->json(['status' => 'error', 'message' => 'You already have an active membership for this plan.'], 400);
        }

        $activeUntil = now()->addMonth();

        $user->memberships()->syncWithoutDetaching([
            $membershipId => ['active_until' => $activeUntil],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Membership booked successfully!',
            'data' => $user->memberships()->withPivot('active_until')->get(),
        ]);
    }

    /**
     * @param int $id
     * @param MembershipUpdateRequest $request
     * @return JsonResponse
     */
    public function update(int $id, MembershipUpdateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $membership = $this->membershipRepository->find($id);
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        $isUpdated = $this->membershipRepository->update($validatedData, $id);

        if ($isUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Membership updated successfully!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update membership!',
        ], 500);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $membership = $this->membershipRepository->find($id);
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        $deleted = $this->membershipRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Membership deleted successfully!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete membership!',
        ], 500);
    }
}
