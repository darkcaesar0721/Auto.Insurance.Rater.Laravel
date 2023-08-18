<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function makes() {
        return $this->hasMany(Make::class);
    }
}
