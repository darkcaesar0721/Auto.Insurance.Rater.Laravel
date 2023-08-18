<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralSource extends Model
{
    protected $fillable = [
	    'referral_company',
        'referral_first_name',
        'referral_last_name',
        'referral_address_line_1',
        'referral_address_line_2',
        'referral_city',
        'referral_state_id',
        'referral_zip',
        'referral_work',
        'referral_cell',
        'referral_fax',
        'referral_email',
        'referral_website',
        'note',
        'referral_tax_id',
        'referral_license'
	];

	protected static $columns = [
		'referral_company',
        'referral_first_name',
        'referral_last_name',
        'referral_address_line_1',
        'referral_address_line_2',
        'referral_city',
        'referral_state_id',
        'referral_zip',
        'referral_work',
        'referral_cell',
        'referral_fax',
        'referral_email',
        'referral_website',
        'note',
        'referral_tax_id',
        'referral_license'
	];

	protected $dates = [
		'created_at',
		'updated_at'
	];

	public static function deleteReferral($referralId)
	{
		ReferralSource::where('id',$referralId)->delete();
	}

	public static function addNewReferralSource($newReferralSourceData)
	{
		$data = [];
		foreach ($newReferralSourceData as $field => $value) {
			if (in_array($field, self::$columns)) {
				$data[$field] = $value;
			}
		}

		return self::create($data);
	}

	public static function updateReferralSourceInfo($referralSourceData)
	{
		$data = [];
		foreach ($referralSourceData as $field => $value) {
			if (in_array($field, self::$columns)) {
				$data[$field] = $value;
			}
		}
		return self::where('id', $referralSourceData['id'])
			->update($data);
	}
	public function states(){
		return $this->belongsTo(States::class, 'referral_state_id');
	}
}
