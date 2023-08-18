<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeZone;

class AutoclubCsv extends Model
{
    protected $fillable = [
    	'name',
    	'client_id',
    	'terminated_at'
    ];

    public function getCreatedAtAttribute($val) {
        $date = date_create_from_format('Y-m-d H:i:s', $val);
        if ($date) {
        	$date -> setTimezone(new DateTimeZone('Pacific/Pitcairn'));
    	}
    	else {
    		return null;
    	}
        return $date->format('Y-m-d H:i:s');
    }

    public function getTerminatedAtAttribute($val) {
        $date = date_create_from_format('Y-m-d H:i:s', $val);
        if ($date) {
        	$date -> setTimezone(new DateTimeZone('Pacific/Pitcairn'));
    	}
    	else {
    		return null;
    	}
        return $date->format('Y-m-d H:i:s');
    }
}

