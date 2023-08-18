<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientPolicy extends Model
{
	protected $fillable = [
                'client_id',
                'company_list_id',
                'effective_date',
                'term',
                'expiration_date',
                'policy_number',
                'premium',
                'co_fees',
                'broker_fee',
                'agency_total',
                'company_total',
                'is_endorsement',
                'paymentm_method_option',
                'check_no',
                'company_down_payment',
                'monthly_payment',
                'referral_fee_option',
                'amount',
                'total_down_payment',
                'referral_source_id'
	];

	protected $dates = [
		'created_at',
		'updated_at'
	];

        public static function deleteClientPolicy($clientId)
        {
                ClientPolicy::where('client_id',$clientId)->delete();
        }

        public function company() {
                return $this->belongsTo(Company::class, 'company_list_id');
        }

	public function policyType() {
		return $this->belongsTo(PolicyTypes::class);
	}

        public function referralSource() {
                return $this->belongsTo(ReferralSource::class, 'referral_source_id');
        }

        public function getPolicyNumberAttribute($policyNumber) {
                return strtoupper($policyNumber);
        }

        public function getMonthlyPaymentAttribute($monthly) {
                return number_format(
                        (float) $monthly, 2, '.', ''
                );
        }

        public function getEffectiveDateAttribute($effectiveDate) {
                if (isset($effectiveDate)) {
                        return date('m/d/Y', strtotime($effectiveDate));
                }
                return date('m/d/Y');
        }

        public function getExpirationDateAttribute($expirationDate) {
                if (isset($expirationDate)) {
                        return date('m/d/Y', strtotime($expirationDate));
                }
                return date('m/d/Y');
        }
}
