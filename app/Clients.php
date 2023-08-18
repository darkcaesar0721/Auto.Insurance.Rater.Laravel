<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;
use mikehaertl\pdftk\Pdf;
use App\ReferralSource;
use Auth;
use Carbon\Carbon;
use DB;
use App\ClientNumber;

class Clients extends Model
{
	const TWILIO_ACCOUNT_SID = 'AC6095868a3b0024a0e5ce6a2ad9f37f57';
	const TWILIO_AUTH_TOKEN = 'f4c8bb5f424e56297226d5bd28a7c35c';
	const TWILIO_SENDER_PHONE = '+17143160251';

	const PHONE_PREFIX = '+1';

	protected $fillable = [
		'client_type_id',
		'policy_type_id',
		'business_name',
		'first_name',
		'middle_name',
		'last_name',
		'suffix',
		'current_address_line_1',
		'current_address_line_2',
		'current_address_zip_code',
		'current_address_county',
		'current_address_address_city',
		'current_address_address_state_id',
		'home_phone',
		'email_address',
		'no_email',
		'cell_phone',
		'cell_phone_2',
		'work_phone',
		'fax_number',
		'preferred_contact_method_id',
		'mailing_address',
		'mailing_address_line_1',
		'mailing_address_line_2',
		'mailing_address_zip_code',
		'mailing_address_county',
		'mailing_address_city',
		'mailing_address_state_id',
		'additional_insured_first_name',
		'additional_insured_middle_name',
		'additional_insured_last_name',
		'additional_insured_suffix',
		'additional_insured_co_applicant',
		'source',
		'agent_id',
		'language_spoken',
        'attachment_file_1',
        'notes',
        'verification',
        'verification_code',
        'auto_club',
        'auto_club_license_only',
        'client_country_id',
        'nationality_id',
        'client_height_id',
        'client_class_id',
        'client_sex_id',
        'client_eyes_id',
        'client_date_of_birth'
	];

	protected static $columns = [
		'client_type_id',
		'policy_type_id',
		'business_name',
		'first_name',
		'middle_name',
		'last_name',
		'suffix',
		'current_address_line_1',
		'current_address_line_2',
		'current_address_zip_code',
		'current_address_county',
		'current_address_address_city',
		'current_address_address_state_id',
		'home_phone',
		'email_address',
		'no_email',
		'cell_phone',
		'cell_phone_2',
		'work_phone',
		'fax_number',
		'preferred_contact_method_id',
		'mailing_address',
		'mailing_address_line_1',
		'mailing_address_line_2',
		'mailing_address_zip_code',
		'mailing_address_county',
		'mailing_address_city',
		'mailing_address_state_id',
		'additional_insured_first_name',
		'additional_insured_middle_name',
		'additional_insured_last_name',
		'additional_insured_suffix',
		'additional_insured_co_applicant',
		'client_number',
		'source',
		'agent_id',
		'language_spoken',
        'attachment_file_1',
        'notes',
        'verification',
        'verification_code',
		'auto_club',
        'auto_club_license_only',
        'client_country_id',
        'nationality_id',
        'client_height_id',
        'client_class_id',
        'client_sex_id',
        'client_eyes_id',
        'client_date_of_birth'
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'client_date_of_birth'
	];

	public static function deleteClient($clientId)
	{
		Clients::where('id',$clientId)->delete();
		if(\Schema::hasTable('client_numbers')) {
			$client_no = ClientNumber::where('client_id', $clientId)->first();
	    	if ($client_no) {
	    		$client_no->delete();
	    	}
		}
	}

	public static function updateClientInfo($clientData)
	{
		$client = self::where('id', $clientData['client_id'])->first();
		if ($client) {
			foreach ($clientData as $field => $value) {
				if (in_array($field, self::$columns)) {
					$client[$field] = $value;
				}
			}

			if (array_key_exists('international', $clientData)) {
		        $client['current_address_address_state_id'] = null;
		        $client['mailing_address_state_id'] = null;
		    }
		    return $client->update();
		}
		return false;
	}

	public static function addNewClient($newClientData)
	{
		$client = new self();

		foreach ($newClientData as $field => $value) {
			if (in_array($field, self::$columns) && $field !== 'client_number') {
				$client[$field] = $value;
				unset($newClientData[$field]);	
			}
		}
		
		$status = $client->save();
		$newClientData['client_id'] = $client->id;

		if ($status && count($newClientData) > 0) {
			self::updateClientInfo($newClientData);
		}

		return $client;
	}

	public function appendNote($text) {
		if (!$this->notes) {
 			$this->notes = '';  
        }
        else {
        	$this->notes .= '
';
 		}
        $this->notes .= $text;
        $this->update();
	}

	public function policies() {
		return $this->hasMany(ClientPolicy::class, 'client_id');
	}

	public function autoClub() {
		return $this->belongsTo(ClientAutoClub::class, 'id', 'client_id');
	}

	public function licenseOnly() {
		return $this->belongsTo(ClientLicenseOnly::class, 'id', 'client_id');
	}

	public function state() {
		return $this->belongsTo(States::class, 'current_address_address_state_id');
	}

	public function policyType() {
		return $this->belongsTo(PolicyTypes::class);
	}

	public function clientCountry() 
    {
        return $this -> belongsTo('App\ClientCountry', 'client_country_id') -> select('id', 'country', 'alpha2', 'alpha3');
    }

	public function getCountry()
    {
        return $this->clientCountry->country ?? '';
    }

	public function clientHeight() 
    {
    	return $this -> belongsTo('App\ClientHeight', 'client_height_id') -> select('id', 'height');
    }

	public function getHeight()
    {
        return $this->clientHeight->height ?? '';
    }    

    public function clientEyes() 
    {
    	return $this -> belongsTo('App\ClientEyes', 'client_eyes_id') -> select('id', 'eyes');
    }

	public function getEyes()
    {
        return $this->clientEyes->eyes ?? '';
    }

    public function clientClass() 
    {
        return $this -> belongsTo('App\ClientClass', 'client_class_id') -> select('id', 'class');
    }

    public function getClass()
    {
        return $this->clientClass->class ?? '';
    }

    public function clientSex() 
    {
        return $this -> belongsTo('App\ClientSex', 'client_sex_id') -> select('id', 'sex');
    }

    public function getSex()
    {
        return $this->clientSex->sex ?? '';
    }

    public function clientnNationality() 
    {
    	return $this -> belongsTo('App\ClientCountry', 'nationality_id') -> select('id', 'country', 'alpha2', 'alpha3');
    }

    public function getNationality()
	{
		return $this->clientnNationality->country ?? $this->getCountry();
	}

    public function agent() 
    {
        return $this -> belongsTo('App\User', 'agent_id') -> select('id', 'name');
    }

    public function clinetNo()
    {
    	return $this -> hasOne('App\ClientNumber', 'client_id')->select('number');
    }

    public function getClientNumberAttribute($val) {
    	if(!\Schema::hasTable('client_numbers')) {
    		if ($this->attributes['client_number']) {
    			return $this->attributes['client_number'];
    		}
    	}
    	else {
    		if (array_key_exists('client_number', $this->attributes)) {
				return $this->attributes['client_number'];
    		}
    		return $this->clinetNo ? $this->clinetNo->number : null;
    	}
    } 

    public function setClientNumberAttribute($val) {
    	if(\Schema::hasTable('client_numbers')) {
    		if (array_key_exists('client_number', $this->attributes)) {
    			return $this->attributes['client_number']; 
    		}
    		$client_no = ClientNumber::where('client_id', $this->id)->first();
	    	if ($client_no && $client_no->number === $val) {
	    		return;
	    	}
	    	$manuall = false;
	    	if ($client_no) {
	    		$client_no->delete();
	    		$manuall = true;
	    	}
    		$client_no = new ClientNumber();
	    	$client_no->number = $val;
	    	$client_no->client_id = $this->id;
	    	$client_no->added_manually = $manuall;
	    	$client_no->save();
    	}
    	else {
    		$this->attributes['client_number'] = $val;
    	}
    }

    public function getClientDateOfBirthAttribute($val) {
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
	
	public function setClientDateOfBirthAttribute($val) {
		if (!$val || gettype($val) === 'object')
		{
			$this->attributes['client_date_of_birth'] = $val;
		}
        else {
        	$val = str_replace('-', '/', $val);
            $format = 'm/d/Y';
            $d = \DateTime::createFromFormat($format, $val);
            
            if ($d && $d->format($format) === $val) {
                $this->attributes['client_date_of_birth'] = $d->format('Y-m-d'); 
            }   
            else {
                dd("client_date_of_birth set error, wrong format (". $val . ") expected: ". $format);
            }
        }
    }

	public static function getFieldValue($clientId, $fieldName)
	{
		$row = self::select($fieldName)->where('id', '=', $clientId)->first();
		return $row->{$fieldName};
	}

	public function isValidPhone($phoneNumber)
	{
		$base_url = "https://lookups.twilio.com/v1/PhoneNumbers/{$phoneNumber}?Type=carrier";
		$ch = curl_init($base_url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, self::TWILIO_ACCOUNT_SID . ':' . self::TWILIO_AUTH_TOKEN);

		$response = curl_exec($ch);
		$response = json_decode($response);

		if (isset($response->status) && $response->status == 404) {
			return false;
		}
		return (is_null($response->carrier->error_code));
	}

	public function generateVerificationCode()
	{
		$rand = substr(md5(microtime()),rand(0,26),5);
		$this->verification_code = $rand;
		$this->save();

		if ($this->isValidPhone(self::PHONE_PREFIX . $this->cell_phone)) {
			$twilio = new Client(self::TWILIO_ACCOUNT_SID, self::TWILIO_AUTH_TOKEN);

	        $twilio->messages->create(
	            self::PHONE_PREFIX . $this->cell_phone,
	            array(
	                'from' => self::TWILIO_SENDER_PHONE,
	                'body' => 'Your Insura verification code is ' . $this->verification_code
	            )
	        );
		}
	}

	public static function getClientsList()
	{
		return self::orderBy('created_at', 'desc')->get();
	}

	public function getEffectiveDateAttribute($val) {
		return date('m/d/Y', strtotime($val));
	}

	public function getExpirationDateAttribute($val) {
		return date('m/d/Y', strtotime($val));
	}
	
	public function getIsClientAttribute()
	{
		return $this->client_type_id == ClientTypes::TYPE_CLIENT;
	}

	public static function trackingAjax($start, $length, $search, $draw) {
        $query = Clients::select(
                    'clients.id', 
                    'clients.client_type_id', 
                    'clients.policy_type_id', 
                    'clients.first_name', 
                    'clients.last_name', 
                    'clients.current_address_line_1', 
                    'clients.current_address_zip_code', 
                    'clients.cell_phone', 
                    'clients.auto_club_license_only',
                    'clients.auto_club'
                );

		if (strlen($search)) {
			$query->leftJoin('client_policies', 	'client_policies.client_id', 		'=', 'clients.id')
				  ->leftJoin('client_auto_club', 	'client_auto_club.client_id', 		'=', 'clients.id')
				  ->leftJoin('client_license_only', 'client_license_only.client_id', 	'=', 'clients.id')
				  ->leftJoin('client_numbers', 		'client_numbers.client_id', 		'=', 'clients.id');
						
			$query->where(function($query) use ($search) {
				$date = null;
				if (strtotime($search)) {
					$d = \DateTime::createFromFormat('m/d/Y', $search);
					if($d && $d->format('m/d/Y') === $search) {
						$date = $d->format('Y-m-d');
					}
				}

				if(!$date) {
					$referal_id = null;
					$policy_type_id = null;
					$auto_club = false;
					$auto_club_license_only = false;

					$ref = ReferralSource::where('referral_company', 'LIKE', '%'. $search . '%')->first();
					if ($ref) {
						$referal_id = $ref->id;
					}

					$pls = PolicyTypes::where('name', 'LIKE', '%'. $search . '%')->first();
					if ($pls) {
						$policy_type_id = $pls->id;
						if($pls->id === \App\PolicyTypes::TYPE_AUTO_CLUB) {
							$auto_club = true;
						}
						else if ($pls->id === \App\PolicyTypes::TYPE_LICENSE_ONLY) { 
							$auto_club_license_only = true;
						}
					}

					$query->orWhere('client_numbers.number', 				'LIKE',	'%'. $search . '%')
					  	  ->orWhere('client_policies.policy_number',		'LIKE',	'%'. $search . '%')
					  	  ->orWhere('client_auto_club.member_id',			'LIKE',	'%'. $search . '%')
					  	  ->orWhere('client_license_only.license_number',	'LIKE',	'%'. $search . '%')
					  
					      ->orWhere('cell_phone',							'LIKE',	'%'. $search . '%')
					  
					      ->orWhere(DB::raw("CONCAT(clients.first_name,' ',clients.last_name)"), 			'LIKE', '%'. $search . '%')
					      ->orWhere(DB::raw("CONCAT(current_address_line_1,' ',current_address_zip_code)"), 'LIKE', '%'. $search . '%');

					if ($auto_club) {
						$query->orWhere('clients.auto_club', $auto_club);
					}

					if ($auto_club_license_only) {
						$query->orWhere('clients.auto_club_license_only', $auto_club_license_only);
					}

					if ($referal_id) {
						$query->orWhere('client_policies.referral_source_id', 		$referal_id)
					  		  ->orWhere('client_auto_club.referral_source_id', 		$referal_id)
					  		  ->orWhere('client_license_only.referral_source_id', 	$referal_id);
					}

					if ($policy_type_id) {
						$query->orWhere('clients.policy_type_id', 					$policy_type_id);	
					}
				}
				else {
					$query->orWhere('client_policies.effective_date',  		$date)
					  	  ->orWhere('client_auto_club.effective_date', 		$date)
					      ->orWhere('client_license_only.effective_date', 	$search)
					  
					      ->orWhere('client_policies.expiration_date',		$date)
					      ->orWhere('client_auto_club.expiration_date',	  	$date)
					      ->orWhere('client_license_only.expiration_date',	$search);
				}
			});

			$query->groupBy('clients.id');
			$total = count($query->get()->toArray());
		}
		else {
			$total = $query->count();
		}

		$clients = $query->skip($start)
						 ->take($length)
						 ->orderBy('clients.id', 'desc')
						 ->get();

		$data = [];

		foreach ($clients as $c) {
			$effective_date = '';
			$expiration_date = '';
			$policy = '';
			$referral_source = '';
			$policyType = '';

			$referalAutoClub = '-';

			if ($c->autoClub && $c->autoClub->referral_source_id) {
				$autoClubRef = ReferralSource::where('id', $c->autoClub->referral_source_id)->first();
				if ($autoClubRef) {
					switch ($c->autoClub->payment_method) {
			            case 'direct cash':
			                $referalAutoClub = '-';
			                break;
			            case 'direct credit card':
			                $referalAutoClub = '-';
			                break;
			            case 'direct other':
			                $referalAutoClub = '-';
			                break;
			            case null:
			                $referalAutoClub = '-';
			                break;
			            default:
			            	$referalAutoClub = $autoClubRef->referral_company;
			            	break;
			        }
				}
			}

			if ($c->policyType && $c->policyType->id == \App\PolicyTypes::TYPE_AUTO_CLUB) {
				$effective_date = $c->autoClub ? $c->autoClub->effective_date : '-';
				$expiration_date = $c->autoClub ? $c->autoClub->expiration_date : '-';
				$policy = $c->autoClub ? $c->autoClub->member_id : '-';
				$referral_source = $referalAutoClub;
				$policyType.= "Auto&nbsp;Club";
				
				if ($c->auto_club_license_only) {
					$effective_date .= "<br>". ($c->licenseOnly ? $c->licenseOnly->effective_date : '-');
                    $expiration_date .= "<br>". ($c->licenseOnly ? $c->licenseOnly->expiration_date : '-');
                    $policy .= "<br>". ($c->licenseOnly ? $c->licenseOnly->license_number : '-');
                    $referral_source .= "<br>".(($c->licenseOnly && $c->licenseOnly->referralSource) ? $c->licenseOnly->referralSource->referral_company : '-');
					$policyType.='<br>License';
				}
			}
			elseif ($c->policyType && $c->policyType->id == \App\PolicyTypes::TYPE_LICENSE_ONLY) {
				$effective_date = ($c->licenseOnly ? $c->licenseOnly->effective_date : '-');
                $expiration_date = ($c->licenseOnly ? $c->licenseOnly->expiration_date : '-');
                $policy = ($c->licenseOnly ? $c->licenseOnly->license_number : '-');
                $referral_source = (($c->licenseOnly && $c->licenseOnly->referralSource) ? $c->licenseOnly->referralSource->referral_company : '-');
				$policyType ='License';
			}
			else {
				$policyType = $c->policyType ? $c->policyType->name : '';
				$effective_date = $c->policies->count() ? $c->policies->first()->effective_date : '-';
            	$expiration_date = $c->is_client && $c->policies->count() ? $c->policies->first()->expiration_date : '-';
                $policy = $c->is_client && $c->policies->count() ? $c->policies->first()->policy_number : '-';
                $referral_source = $c->policies->count() && $c->policies()->first() && $c->policies()->first()->referralSource ? $c->policies()->first()->referralSource->referral_company : '-';

                if ($c->policyType && $c->policyType->id == \App\PolicyTypes::TYPE_PERSONAL && $c->auto_club) {
                    $effective_date .= "<br>". ($c->autoClub ? $c->autoClub->effective_date : '-');
                    $expiration_date .= "<br>". ($c->autoClub ? $c->autoClub->expiration_date : '-');
                    $policy .= "<br>".($c->autoClub ? $c->autoClub->member_id : '-');
                    $referral_source .= "<br>".$referalAutoClub;
                    $policyType.='<br>Auto&nbsp;Club';
                }
                if ($c->policyType && $c->policyType->id == \App\PolicyTypes::TYPE_PERSONAL && $c->auto_club_license_only) {
                    $effective_date .= "<br>". ($c->licenseOnly ? $c->licenseOnly->effective_date : '-');
                    $expiration_date .= "<br>". ($c->licenseOnly ? $c->licenseOnly->expiration_date : '-');
                    $policy .= "<br>". ($c->licenseOnly ? $c->licenseOnly->license_number : '-');
                    $referral_source .= "<br>".(($c->licenseOnly && $c->licenseOnly->referralSource) ? $c->licenseOnly->referralSource->referral_company : '-');
                	$policyType.='<br>License';
                }
			}

            $arr = array(
				$effective_date,
				$expiration_date,
				ucwords($c->first_name) . ' ' . ucwords($c->last_name),
				$c->is_client ? $c->client_number : '-',
				$policy,
				$c->cell_phone,
				$policyType,
				ucwords($c->current_address_line_1).' '.$c->current_address_zip_code,
				$referral_source
			);

            $lasttd = '<td>
               				<a href="/admin/clients/edit/'. $c->id.'"><i class="edit-icon mdi mdi-pencil"></i></a>';

			if (Auth::user()->is_admin) {
               $lasttd .= '
               <a href="/admin/client/delete/'.$c->id.'"><i class="delete-icon mdi mdi-delete-forever"></i></a>';
			}
			$lasttd .= '</td>';
			$arr[] = $lasttd;
			array_push($data,$arr);
		}

		return json_encode(array(
		 	'draw' => $draw,
			'recordsTotal' => Clients::count(),
			'recordsFiltered' => $total,
			'data' => $data
		));		
	}
}