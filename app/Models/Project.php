<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'budget' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
