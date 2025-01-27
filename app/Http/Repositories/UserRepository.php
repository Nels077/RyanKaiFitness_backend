<?php

namespace App\Http\Repositories;

use App\Http\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $user)
    {
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->user->with(['memberships', 'fitnessClasses'])->get();
    }

    /**
     * @param array $data
     * @return User|null
     */
    public function login(array $data): User|null
    {
        return $this->user->where('email', '=', $data['email'])->first();
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): User|null
    {
        return $this->user->find($id)->with(['memberships', 'fitnessClasses'])->first();
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return $this->user->find($id)->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->user->destroy($id);
    }
}
