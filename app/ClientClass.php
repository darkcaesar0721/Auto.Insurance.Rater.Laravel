<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientClass extends Model
{
    protected $table = 'client_classes';

    protected $fillable = [
    	'class'
    ];
    
}
