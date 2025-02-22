<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sell extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}