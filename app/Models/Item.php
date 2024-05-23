<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $guarded = [''];
    public function inOuts()
    {
        return $this->hasMany(InOut::class, 'items_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}