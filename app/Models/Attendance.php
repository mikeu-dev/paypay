<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, \App\Traits\BelongsToTenant;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    protected $fillable = [
        'team_id',
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'fingerprint',
        'coordinate',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i:s', // Or just string if using time
            'end_time' => 'datetime:H:i:s',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
