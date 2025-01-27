<?php

namespace App\Http\Contracts;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Collection;

interface MembershipRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Membership|null;

    public function create(array $data): Membership;

    public function update(array $data, int $id): bool;

    public function delete(int $id): int;
}
