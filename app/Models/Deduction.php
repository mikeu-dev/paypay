<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'team_id',
        'name',
        'type',
        'value',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_deductions')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
