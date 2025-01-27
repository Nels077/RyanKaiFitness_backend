<?php

namespace App\Http\Repositories;

use App\Http\Contracts\MembershipRepositoryInterface;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Collection;

class MembershipRepository implements MembershipRepositoryInterface
{
    public function __construct(protected Membership $membership)
    {
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->membership->with('benefits')->get();
    }

    /**
     * @param int $id
     * @return Membership|null
     */
    public function find(int $id): Membership|null
    {
        return $this->membership->with('benefits')->find($id)->first();
    }

    /**
     * @param array $data
     * @return Membership
     */
    public function create(array $data): Membership
    {
        return $this->membership->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return $this->membership->find($id)->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->membership->destroy($id);
    }
}
