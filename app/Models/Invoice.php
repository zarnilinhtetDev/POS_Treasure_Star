<?php

namespace App\Models;

use App\Models\Sell;
use App\Models\PO_sells;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guraded = [];
    public function sells()
    {
        return $this->hasMany(Sell::class, 'invoiceid');
    }

    public function po_sells()
    {
        return $this->hasMany(PO_sells::class, 'invoiceid');
    }
}