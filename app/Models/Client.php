<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, SoftDeletes, Notifiable, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'team_id',
    ];


}
