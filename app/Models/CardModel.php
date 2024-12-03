<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardModel extends Model
{
    protected $table = 'cards';
    protected $guarded = [];
    

    public function column()
    {
        return $this->belongsTo(ColumnModel::class);
    }
}
