<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProjectModel extends Model
{
    use HasFactory;
    protected $table = 'project';
    protected $guarded = [];


    public function columns()
    {
        return $this->hasMany(ColumnModel::class, 'project_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

}
