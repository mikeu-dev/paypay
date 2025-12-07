<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'name',
        'amount',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
