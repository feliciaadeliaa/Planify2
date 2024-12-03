<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $table = 'tasks';
    protected $guarded = [];

    public function subTask() :HasMany
    {
        return $this->hasMany(SubTask::class);
    }
}
