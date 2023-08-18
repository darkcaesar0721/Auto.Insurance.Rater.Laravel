<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	protected $fillable = [
        'date_from',
        'date_to',
        'effective_date',
        'expiration_date',
        'broker_fee',
        'by_company',
        'company_premium',
        'down_payment',
        'referral_fee'
    ];

    public static function saveReport($input) {
    	$report = self::first();
    	$report->date_from = isset($input['date_from']) ? date('Y-m-d', strtotime($input['date_from'])) : date('Y-m-d');
    	$report->date_to = isset($input['date_to']) ? date('Y-m-d', strtotime($input['date_to'])) : date('Y-m-d');
    	$report->effective_date = isset($input['effective_date']) ? 1 : 0;
    	$report->expiration_date = isset($input['expiration_date']) ? 1 : 0;
    	$report->broker_fee = isset($input['broker_fee']) ? 1 : 0;
    	$report->by_company = isset($input['by_company']) ? 1 : 0;
    	$report->company_premium = isset($input['company_premium']) ? 1 : 0;
    	$report->down_payment = isset($input['down_payment']) ? 1 : 0;
    	$report->referral_fee = isset($input['referral_fee']) ? 1 : 0;
    	$report->updated_at = date('Y-m-d H:i:s');
    	$report->save();
    }
	public function getDateFromOriginAttribute() {
		return date('Y-m-d', strtotime($this->date_from)) . ' 00:00:00';
	}

	public function getDateToOriginAttribute() {
		return date('Y-m-d', strtotime($this->date_to)) . ' 23:59:59';
	}

	public function getDateFromAttribute($dateFrom) {
		return date('m/d/Y', strtotime($dateFrom));
	}

	public function getDateToAttribute($dateTo) {
		return date('m/d/Y', strtotime($dateTo));
	}
}
