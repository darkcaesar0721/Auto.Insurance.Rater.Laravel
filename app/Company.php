<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $fillable = [
		'company_name',
		'broker_id',
		'toll_free',
		'claims_phone',
		'fax',
		'email',
		'website',
		'note',
		'payment_address'
	];

	protected static $columns = [
		'company_name',
		'broker_id',
		'toll_free',
		'claims_phone',
		'fax',
		'email',
		'website',
		'note',
		'payment_address'
	];

	protected $dates = [
		'created_at',
		'updated_at'
	];

	public static function deleteCompany($companyId)
	{
		self::where('id', $companyId)->delete();
	}

	public static function addNewCompany($newCompanyData)
	{
		$data = [];
		foreach ($newCompanyData as $field => $value) {
			if (in_array($field, self::$columns)) {
				$data[$field] = $value;
			}
		}

		return self::create($data);
	}

	public static function updateCompanyInfo($companyData)
	{
		$data = [];
		foreach ($companyData as $field => $value) {
			if (in_array($field, self::$columns)) {
				$data[$field] = $value;
			}
		}
		return self::where('id', $companyData['id'])
			->update($data);
	}
}
