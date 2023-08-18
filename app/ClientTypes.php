<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTypes extends Model
{
    protected $fillable = [
    	'name'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
   	];

	const TYPE_PROSPECT = 1;
	const TYPE_CLIENT = 2;
}
