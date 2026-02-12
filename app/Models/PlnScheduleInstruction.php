<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlnScheduleInstruction extends Model
{
    use HasFactory;

    protected $table = 'pln_schedules_has_instructions';

    protected $fillable = [
        'production_schedule_id',
        'instruction_id',
        'status',
        'start_time',
        'end_time',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(PlnProductionSchedule::class, 'production_schedule_id');
    }

    public function instruction()
    {
        return $this->belongsTo(RecipeInstruction::class, 'instruction_id');
    }
}
