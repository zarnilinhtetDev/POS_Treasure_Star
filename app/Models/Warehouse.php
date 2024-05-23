<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [''];

    // public function items()
    // {
    //     return $this->hasMany(Item::class);
    // }

    public function inouts()
    {
        return $this->hasMany(InOut::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'level');
    }
}
