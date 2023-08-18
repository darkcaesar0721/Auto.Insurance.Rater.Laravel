<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\PaymentMethod;

class ClientAutoClub extends Model
{
    protected $table = 'client_auto_club';

    protected $fillable = [
    	'client_id',
    	'member_id',
    	'payment_method_id',
    	'term',
    	'effective_date',
    	'expiration_date',
    	'premium',
    	'co_fees',
    	'down_payment',
    	'referral_amount',
    	'monthly_payment',
    	'company_total',
        'referral_source_id',
        'check_no'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'effective_date',
        'expiration_date'
   	];

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

    public function referralSource() {
        return $this->belongsTo(ReferralSource::class, 'referral_source_id');
    }

    public function pdfDownload(){
        return $this->hasOne(PdfDownload::class, 'client_id', 'client_id');
    }

    public static function getClientsForNotification() {
        $currentDate = date('Y-m-d');
        $expirationDate = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d') + 15), date('Y')));
        return self::select('client_auto_club.id','client_auto_club.client_id', 'client_auto_club.expiration_date')
                    ->where('client_auto_club.expiration_date', '<=', $expirationDate)
                    ->join('clients', 'client_auto_club.client_id', 'clients.id')
                    ->leftJoin('pdf_downloads', 'pdf_downloads.client_id', '=', 'client_auto_club.client_id')
                    ->whereNull('pdf_downloads.id')
                    ->get();
    }

    public function clients() {
        return $this->hasOne(Clients::class, 'id', 'client_id');
    }
    public function CSV() {
        return $this->hasOne(AutoclubCsv::class, 'client_id', 'client_id');
    }
     public function paymentMethod() 
    {
        return $this -> belongsTo('App\PaymentMethod', 'payment_method_id') -> select('id', 'name');
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod->name ?? '';
    }
    
    public static function deleteAutoClub($client_id) {
        ClientAutoClub::where('client_id',$client_id)->delete();
    }

    public function getEffectiveDateAttribute($val) {
        if (!$val) {
            return $val;
        }
        $date = new Carbon($val);
        return $date->format('m/d/Y');
    }

    public function getExpirationDateAttribute($val) {
        if (!$val) {
            return $val;
        }
        $date = new Carbon($val);
        return $date->format('m/d/Y');
    }

    public function setEffectiveDateAttribute($date) {
        if (gettype($date) === 'object') {
            $this->attributes['effective_date'] = $date;
        }
        else {
            $format = 'm/d/Y';
            $d = \DateTime::createFromFormat($format, $date);
            
            if ($d && $d->format($format) === $date) {
                $this->attributes['effective_date'] = $d->format('Y-m-d'); 
            }   
            else {
                dd("effective_date set error, wrong format (". $date . ") expected: ". $format);
            }    
        }
    }

    public function setExpirationDateAttribute($date) {
        if (gettype($date) === 'object') {
            $this->attributes['expiration_date'] = $date;
        }
        else {
            $format = 'm/d/Y';
            $d = \DateTime::createFromFormat($format, $date);
            
            if ($d && $d->format($format) === $date) {
                $this->attributes['expiration_date'] = $d->format('Y-m-d');
            }
            else {
                dd("expiration_date set error, wrong format (". $date . ") expected: ". $format);
            }
        }
    }
}
