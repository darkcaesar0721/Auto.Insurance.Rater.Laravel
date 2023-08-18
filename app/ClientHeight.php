<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHeight extends Model
{
    protected $table = 'client_heights';

    protected $fillable = [
    	'height'
    ];
}
