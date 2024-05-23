<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    public function warehouse()
    {
        return $this->hasMany(Warehouse::class);
    }
}