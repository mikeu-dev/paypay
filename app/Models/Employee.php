<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, \App\Traits\BelongsToTenant;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    protected $fillable = [
        'employee_code',
        'name',
        'position',
        'department',
        'hire_date',
        'base_salary',
        'team_id',
    ];

    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowances')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function deductions()
    {
        return $this->belongsToMany(Deduction::class, 'employee_deductions')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
