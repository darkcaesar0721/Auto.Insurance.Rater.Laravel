<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    protected $fillable = ['year_id', 'name'];

    public $timestamps = false;

    public function vehicleModels() {
        return $this->hasMany(VehicleModel::class);
    }

    public function getNameAttribute($val) {
        return str_replace('amp;', '', $val);
    }

    public function year() {
        return $this->belongsTo(Year::class);
    }
}
