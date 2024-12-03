<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ColumnModel extends Model
{
    protected $table = 'column';
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(ProjectModel::class);
    }

    public function cards()
    {
        return $this->hasMany(CardModel::class, 'column_id');
    }
}
