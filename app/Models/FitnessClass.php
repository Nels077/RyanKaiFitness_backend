<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FitnessClass extends Model
{
    use HasFactory;

    protected $table = 'fitness_classes';

    protected $fillable = ['title', 'subtitle', 'duration', 'working_days', 'price'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'fitness_class_user')
            ->withPivot(['created_at', 'updated_at'])
            ->withTimestamps();
    }
}
