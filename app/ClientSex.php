<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientSex extends Model
{
    protected $table = 'client_sex';

    protected $fillable = [
    	'sex'
    ];
}
