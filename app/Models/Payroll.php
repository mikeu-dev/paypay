<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, \App\Traits\BelongsToTenant;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'basic_salary' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function allowanceDetails()
    {
        return $this->hasMany(PayrollAllowance::class);
    }

    public function deductionDetails()
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}
