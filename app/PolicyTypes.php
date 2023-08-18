<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyTypes extends Model
{
    protected $fillable = [
    	'name'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
   	];

   	const TYPE_PERSONAL = 1;
   	const TYPE_COMMERCIAL = 2;
   	const TYPE_AUTO_CLUB = 3;
    const TYPE_LICENSE_ONLY = 4;
}
