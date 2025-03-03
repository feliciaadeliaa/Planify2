<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'tasks';
    protected $guarded = [];

    public function subTask() :HasMany
    {
        return $this->hasMany(SubTask::class);
    }
}
