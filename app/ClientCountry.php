<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCountry extends Model
{
    protected $table = 'client_countries';

    protected $fillable = [
    	'country',
    	'alpha2',
    	'alpha3'
    ];
}
