<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'memberships';

    protected $fillable = ['title', 'price', 'description', 'subtitle'];

    public function benefits(): HasMany
    {
        return $this->hasMany(MembershipBenefit::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membership_user')
            ->withPivot(['created_at', 'updated_at'])
            ->withTimestamps();
    }
}
