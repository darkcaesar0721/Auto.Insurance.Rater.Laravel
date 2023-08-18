<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientLicenseOnly extends Model
{
    protected $table = 'client_license_only';

    protected $fillable = [
    	'client_id',
    	'license_number',
    	'payment_method_id',
    	'term',
    	'effective_date',
    	'expiration_date',
    	'price',
    	'ship_fee',
    	'total_cost',
    	'company_total',
        'referral_source_id',
        'tracking_no',
        'photo',
        'sign'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
   	];

    public function referralSource() {
        return $this->belongsTo(ReferralSource::class, 'referral_source_id');
    }

    public function paymentMethod() 
    {
        return $this -> belongsTo('App\PaymentMethod', 'payment_method_id') -> select('id', 'name');
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod->name ?? '';
    }

    public function getPaymentMethodAttribute($val) {
        if (isset($this->attributes['payment_method'])) {
            return $val;
        }
        elseif (isset($this->attributes['payment_method_id'])) {
            $payment_method = PaymentMethod::where('id', $this->attributes['payment_method_id'])->first();
            if ($payment_method) {
                return $payment_method->alias;
            }
        }
    }

    public function setPaymentMethodAttribute($var) {
        if (isset($this->attributes['payment_method'])) {
            return $val;
        }
        elseif (isset($this->attributes['payment_method_id'])) {
            $payment_method = PaymentMethod::where('alias', $var)->orWhere('id', $var)->first();
            if ($payment_method) {
                $this->attributes['payment_method_id'] = $payment_method->id;
            }
        }
    }

    public function getLicenseNumberAttribute() {
        if (strtoupper(substr($this->attributes['license_number'], 0, 3)) !== "MWL") {
            $this->attributes['license_number'] = "MWL".$this->attributes['license_number'];
        }
        return strtoupper($this->attributes['license_number']);
    }
    public function setLicenseNumberAttribute($value) {
        if (strtoupper(substr($value, 0, 3)) !== "MWL") {
            $this->attributes['license_number'] = "MWL".strtoupper($value);
        }
        else {
            $this->attributes['license_number'] = strtoupper($value);
        }
    }
    public static function deleteLicenseOnly($client_id) {
        ClientLicenseOnly::where('client_id',$client_id)->delete();
    }

    public function getEffectiveDateAttribute($val) {
        if (!$val) {
            return $val;
        }
        $val = str_replace('-', '/', $val);
        $format = 'Y/m/d';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $date = $d; 
        }
        else {
            $date = new Carbon($val);
        }
        return $date->format('m/d/Y');
    }
    
    public function setEffectiveDateAttribute($val) {
        if (!$val)
        {
            $this->attributes['effective_date'] = null;
            return;
        }

        $val = str_replace('-', '/', $val);
        $format = 'm/d/Y';
        $d = \DateTime::createFromFormat($format, $val);
        
        if ($d && $d->format($format) === $val) {
            $this->attributes['effective_date'] = $d->format('Y-m-d');
        }   
        else {
            $format = 'Y/m/d';
            $d = \DateTime::createFromFormat($format, $val);                
            if (!$d || $d->format($format) !== $val) {
                dd("Wrong format: $format; expected: $val"); 
            }
            else {
                $this->attributes['effective_date'] = $val;
            } 
        }
        
    }

    public function getExpirationDateAttribute($val) {
        if (!$val) {
            return $val;
        }
        $val = str_replace('-', '/', $val);
        $format = 'Y/m/d';
        $d = \DateTime::createFromFormat($format, $val);
        if ($d && $d->format($format) === $val) {
            $date = $d; 
        }
        else {
            $date = new Carbon($val);
        }
        return $date->format('m/d/Y');
    }
    
    public function setExpirationDateAttribute($val) {
        if (!$val)
        {
            $this->attributes['expiration_date'] = null;
            return;
        }

        $val = str_replace('-', '/', $val);
        $format = 'm/d/Y';
        $d = \DateTime::createFromFormat($format, $val);
        
        if ($d && $d->format($format) === $val) {
            $this->attributes['expiration_date'] = $d->format('Y-m-d');
        }   
        else {
            $format = 'Y/m/d';
            $d = \DateTime::createFromFormat($format, $val);                
            if (!$d || $d->format($format) !== $val) {
                dd("Wrong format: $format; expected: $val"); 
            }
            else {
                $this->attributes['expiration_date'] = $val;
            } 
        }
    }
}
