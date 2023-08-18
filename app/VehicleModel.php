<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = [
        'make_id', 'name'
    ];

    public $timestamps = false;

    public function make() {
        return $this->belongsTo(Make::class);
    }

    public function getNameAttribute($val) {
        return str_replace('amp;', '', $val);
    }

    public function subModels() {
        return $this->hasMany(SubModel::class);
    }
}
