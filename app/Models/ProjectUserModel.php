<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUserModel extends Model
{
    use HasFactory;
    protected $table = 'project_user';

    protected $guarded = [];
}