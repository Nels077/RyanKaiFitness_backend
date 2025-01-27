<?php

namespace App\Http\Repositories;

use App\Http\Contracts\FitnessClassRepositoryInterface;
use App\Models\FitnessClass;
use Illuminate\Database\Eloquent\Collection;

class FitnessClassRepository implements FitnessClassRepositoryInterface
{
    /**
     * @param FitnessClass $fitnessClass
     */
    public function __construct(protected FitnessClass $fitnessClass)
    {
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->fitnessClass->all();
    }

    /**
     * @param int $id
     * @return FitnessClass|null
     */
    public function find(int $id): FitnessClass|null
    {
        return $this->fitnessClass->find($id)->first();
    }

    /**
     * @param array $data
     * @return FitnessClass
     */
    public function create(array $data): FitnessClass
    {
        return $this->fitnessClass->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return $this->fitnessClass->find($id)->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->fitnessClass->destroy($id);
    }
}
