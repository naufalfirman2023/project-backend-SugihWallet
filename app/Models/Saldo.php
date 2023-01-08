<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $guarded = [];

    function jalan() {
        return $this->belongsTo(jalan::class);
    }
}
