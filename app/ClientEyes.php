<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientEyes extends Model
{
    protected $table = 'client_eyes';

    protected $fillable = [
    	'eyes'
    ];
}
