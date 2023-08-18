<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeZone;
use Carbon\Carbon;
class WpImportUser extends Model
{
	protected $fillable = [
    	'profile_picture',
		'signature_picture',
		'membership_card',
		'membership_number',
		'license_number',
		'expires_on',
		'given_names',
		'surname',
		'address_line_1',
		'address_line_2',
		'nationality',
		'height_id',
		'eyes',
		'eyes_id',
		'class_id',
		'issued_on',
		'sex_id',
		'birth',
		'email',
		'phone',
		'order_date',
        'nationality_id',
        'order_type',
        'plan',
        'shipping_type',
        'agent_first_name',
        'agent_last_name',
        'agent_address_one',
        'agent_address_two',
        'agent_city',
        'agent_state',
        'agent_zip',
        'agent_tax_id',
        'agent_phone',
        'agent_fax',
        
        //'agent_id'
    ];

    public function clientHeight()
    {
        return $this -> belongsTo('App\ClientHeight', 'height_id') -> select('id', 'height');
    }

    public function clientEyes()
    {
        return $this -> belongsTo('App\ClientEyes', 'eyes_id') -> select('id', 'eyes');
    }

    public function clientClass() 
    {
    	return $this -> belongsTo('App\ClientClass', 'class_id') -> select('id', 'class');
    }

    public function clientSex() 
    {
    	return $this -> belongsTo('App\ClientSex', 'sex_id') -> select('id', 'sex');
    }

    public function clientCountry() 
    {
        return $this -> belongsTo('App\ClientCountry', 'nationality_id') -> select('id', 'country', 'alpha2', 'alpha3');
    }

    public function getHeight() 
    {
    	return $this->clientHeight->height ?? null;
    }

    public function getEyes() 
    {
    	return $this->clientEyes->eyes ?? null;
    }

    public function getClass()
    {
    	return $this->clientClass->class ?? null;
    } 

    public function getSex()
    {
    	return $this->clientSex->sex ?? null;
    }

    public function getCountry()
    {
        return $this->clientCountry->country ?? null;
    }
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

    public function getIssuedOnAttribute($val) {
        $format = 'm-d-Y';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d'); 
        }
        return $val;
    }

    public function setIssuedOnAttribute($val) {
        $format = 'm-d-Y';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d'); 
        }
        $this->attributes['issued_on'] = $val;
    }

    public function getBirthAttribute($val) {
        $format = 'm-d-Y';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d'); 
        }
        return $val;
    }

    public function setBirthAttribute($val) {
        $format = 'm-d-Y';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d'); 
        }
        $this->attributes['birth'] = $val;
    }
    
    public function getOrderDateAttribute($val) {
        $format = 'm/d/Y H:i:s';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d H:i:s'); 
        }
        return $val;
    }
    
    public function setOrderDateAttribute($val) {
        $format = 'm/d/Y H:i:s';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $val = $d->format('Y-m-d H:i:s'); 
        }
        $this->attributes['order_date'] = $val;
    }

    public function getAgentInfo() {
        if (!isset($this['ordered'])) {
            return '';
        }
        $agentName = str_replace('Agent:::', '', $this['ordered']);
        $agentName = str_replace(':::on', '', $agentName);
        $agentName = str_replace(':::', '', $agentName);
        return 'Company name: '     . $agentName . '
Name: '             . ($this->agent_first_name  ?? '') . ' ' . ($this->agent_last_name ?? '') . '
Address line 1: '   . ($this->agent_address_one ?? '') . '
Address line 2: '   . ($this->agent_address_two ?? '') . '
City: '             . ($this->agent_city        ?? '') . '
State: '            . ($this->agent_state       ?? '') . '
Zip: '              . ($this->agent_zip         ?? '') . '
Tax id: '           . ($this->agent_tax_id      ?? '') . '
Phone: '            . ($this->agent_phone       ?? '') . '
Fax: '              . ($this->agent_fax         ?? '');
    }
}


