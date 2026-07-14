<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'sex',
        'hospital_id',
        'contact',
    ];

    public function triageRecords(): HasMany
    {
        return $this->hasMany(TriageRecord::class);
    }
}
