<?php

namespace App\Domain\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    protected $fillable = ['survey_id', 'score', 'payload', 'ip_address', 'user_agent', 'submitted_at'];

    protected $casts = [
        'payload'      => 'array',
        'score'        => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
