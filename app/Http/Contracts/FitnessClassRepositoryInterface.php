<?php

namespace App\Http\Contracts;

use App\Models\FitnessClass;
use Illuminate\Database\Eloquent\Collection;

interface FitnessClassRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): FitnessClass|null;

    public function create(array $data): FitnessClass;

    public function update(array $data, int $id): bool;

    public function delete(int $id): int;
}
