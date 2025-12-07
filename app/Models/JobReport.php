<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class JobReport extends Model implements Sortable
{
    use \App\Traits\BelongsToTenant;
    use SortableTrait;

    protected $guarded = [];

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    const STATUS_WAITING = 'waiting';
    const STATUS_TODO = 'todo';
    const STATUS_ON_PROGRESS = 'on_progress';
    const STATUS_COMPLETE = 'complete';

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
