<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectModel extends Model
{
    protected $table = 'project';
    protected $guarded = [];


    public function columns()
    {
        return $this->hasMany(ColumnModel::class, 'project_id');
    }
}
