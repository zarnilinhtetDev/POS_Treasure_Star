<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MakePayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [''];

    // public function invoice_record()
    // {
    //     return $this->belongsTo(Invoice::class, 'invoice_record');
    // }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
