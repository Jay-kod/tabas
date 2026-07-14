<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'total_beds',
    ];

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }
}
