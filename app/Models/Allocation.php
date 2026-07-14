<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'triage_record_id',
        'recommended_bed_id',
        'status',
        'decided_by',
        'reason',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    public function triageRecord(): BelongsTo
    {
        return $this->belongsTo(TriageRecord::class);
    }

    public function recommendedBed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'recommended_bed_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
