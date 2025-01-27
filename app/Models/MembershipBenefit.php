<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipBenefit extends Model
{
    use HasFactory;

    protected $table = 'benefits';

    protected $fillable = ['membership_id', 'text'];

    public function membership() : BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}
