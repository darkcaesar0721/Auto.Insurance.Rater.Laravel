<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubModel extends Model
{
    protected $fillable = [
        'vehicle_model_id', 'name'
    ];

    public $timestamps = false;

    public function getNameAttribute($val) {
        return str_replace('amp;', '', $val);
    }

    public function vehicleModel() {
        return $this->belongsTo(VehicleModel::class);
    }
}
