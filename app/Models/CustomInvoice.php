<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomInvoice extends Model
{
    use HasFactory;
    public function sells()
    {
        return $this->hasMany(CustomSell::class, 'custom_invoiceid');
    }
}
