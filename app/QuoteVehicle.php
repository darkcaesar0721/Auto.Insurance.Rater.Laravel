<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteVehicle extends Model
{
    protected $fillable = [
        'vin_no',
        'year', 'make', 'model', 'sub_model', 'coverage',
        'usage', 'alarm'
    ];
}
