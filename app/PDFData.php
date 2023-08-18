<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clients;
use mikehaertl\pdftk\Pdf;
use mikehaertl\pdftk\Command;
use App\Company;
use App\ReferralSource;

class PDFData extends Model
{
    public static function getFormData($pdfId, $client_id)
	{
		$client = Clients::where('id', $client_id)->first();
		if (!$client) {
			return [];
		}
		if ($pdfId == 1) {
			$police = $client->policies()->first();
			return [
				'From-Agent' => $client->agent ? $client->agent->name : '',
				'Down Payment' => $police ? $police->total_down_payment : '',
				'Monthly' => $police ? $police->monthly_payment : '',
				'Customers Name' => $client->first_name . ' ' . $client->last_name,
				'Date' => date('m/d/Y'),
				'Policy' => $police ? $police->policy_number : '',
				'Check Box5' => $police ? ($police->term == 'monthly' ? 'Yes' : 'No') : 'No',
				'Check Box6' => $police ? ($police->term == '3_months' ? 'Yes' : 'No') : 'No',
				'Check Box7' => $police ? ($police->term == '6_months' ? 'Yes' : 'No') : 'No',
				'Check Box8' => $police ? ($police->term == 'year' ? 'Yes' : 'No') : 'No',
				'FEE' => $police ? $police->broker_fee : '',
				'Reg Services' => $police ? ($police->referralSource ?
					$police->referralSource->referral_company :
					'') : ''
	        ];
		}
		if ($pdfId == 2) {
			$police = $client->policies()->first();
			$endoPolicy = $client->policies()->where('is_endorsement', 1)->first();
			$endoFee = $endoPolicy ? $endoPolicy->total_down_payment : '';
			$endoBrokerFee = $endoPolicy ? $endoPolicy->broker_fee : '';

			return [
				'From' => $client->agent ? $client->agent->name : '',
				'Customers Name' => $client->first_name . ' ' . $client->last_name,
				'Date' => date('m/d/Y'),
				'Policy' => $police ? $police->policy_number : '',
				'BROKER FEE' => $endoBrokerFee,
				'Endo FeePay' => $endoFee,
				'Pay' => $client->policies()->where('is_endorsement', 1)->first() ? 
				$client->policies()->where('is_endorsement', 1)->first()->monthly_payment : '',
				'Reg Services' => $police ? ($police->referralSource ?
					$police->referralSource->referral_company :
					'') : '' 
			];
		}
		if ($pdfId == 3) {
			// return [
			// 	'Name' => $client->first_name,
			// 	'Name1' => $client->last_name,
			// 	'Address' => $client->current_address_line_1,
			// 	'Address2' => $client->current_address_line_2,
			// 	'City' => $client->current_address_address_city,
			// 	'State' => $client->state()->first()->name,
			// 	'Zip' => $client->current_address_zip_code,
			// 	'Insurance Company' => $client->policies()->first()->company->company_name,
			// 	'Policy No' => $client->policies()->first()->policy_number,
			// 	'Company Payment Address' => $client->policies()->first()->company->payment_address,
			// 	'Company Phone Number' => $client->policies()->first()->company->claims_phone,
			// 	'Effective Date' => $client->policies()->first()->effective_date,
			// 	'Down Payment' => $client->policies()->first()->total_down_payment,
			// 	'Payment(s)' => $client->policies()->first()->monthly_payment,
			// 	'Date' => date('m/d/Y'),
			// 	'Agent Name' => $client->agent
			// ];
		}
		if ($pdfId == 4) {
			$police = $client->policies()->first();
			return [
				'Reg Services' => $police ? ($police->referralSource ?
					$police->referralSource->referral_company :
					'') : '',
				'From-Agent' => $client->agent ? $client->agent->name : '',
				'Payment Amount' => $police ? $police->total_down_payment : '',
				'POLICY NUMBER' => $police ? $police->policy_number : '',
				'POLICY HOLDERS NAME' => $police ? $police->company->company_name : '',
				'PAY DATE' => date('m/d/Y')
			];
		}
		if ($pdfId == 5) {
			$additionalInsuredInfo = '';

			if ($client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL) {
				$additionalInsuredInfo = $client->additional_insured_first_name . ' ' . 
					$client->additional_insured_middle_name . ' ' . 
					$client->additional_insured_last_name . "\n" . 
					$client->business_name . "\n";
			}

			$police = $client->policies()->first();
			return [
				'State' => $client->state()->first() ? $client->state()->first()->name : '',
				'Company No' => $police ? $police->company->toll_free : '',
				'CompanyName' => $police ? $police->company->company_name: '',
				'PolicyNumber' => $police ? $police->policy_number : '',
				'PolicyEffective' => $police ? $police->effective_date : '',
				'PolicyExpires' => $police ? $police->expiration_date : '',
				'commercial1' => $client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL ? 'Yes' : 'No',
				'personal1' => $client->policy_type_id == PolicyTypes::TYPE_PERSONAL ? 'Yes' : 'No',
				'commercial2' => $client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL ? 'Yes' : 'No',
				'personal2' => $client->policy_type_id == PolicyTypes::TYPE_PERSONAL ? 'Yes' : 'No',
				'commercial3' => $client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL ? 'Yes' : 'No',
				'personal3' => $client->policy_type_id == PolicyTypes::TYPE_PERSONAL ? 'Yes' : 'No',
				'commercial4' => $client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL ? 'Yes' : 'No',
				'personal4' => $client->policy_type_id == PolicyTypes::TYPE_PERSONAL ? 'Yes' : 'No',
				'InsuredMailName' => $client->first_name . ' ' . $client->last_name . "\n" . 
					$additionalInsuredInfo .
					$client->current_address_line_1 . "\n" . 
					$client->current_address_line_2 . "\n" . 
					$client->current_address_address_city . ' ' .
					$client->state()->first()->name . ' ' .
					$client->current_address_zip_code
			];
		}
		if ($pdfId == 6) {
			$police = $client->policies()->first();
			return [
				'Company Phone' => $client->cell_phone,
				'Company Name' => $police ? $police->company->company_name : '',
				'Policy No' => $police ? $police->policy_number : '',
				'Effective Date' => $police ? $police->effective_date : '',
				'Experation Date' => $police ? $police->expiration_date : '',
				'Check Box1' => $client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL ? 'Yes' : 'No',
				'Check Box2' => $client->policy_type_id == PolicyTypes::TYPE_PERSONAL ? 'Yes' : 'No',
				'Insured Name' => $client->first_name . ' ' . $client->last_name,
				'Insured Name2' => $client->additional_insured_first_name ? 
					$client->additional_insured_first_name . ' ' .
					$client->additional_insured_middle_name . ' ' . 
					$client->additional_insured_last_name
					: '',
				'Insured Address' => $client->current_address_line_1 . ' ' . $client->current_address_line_2,
				'Insured City' => $client->current_address_address_city,
				'Insured State' => $client->state ? $client->state->name : '',
				'Insured Zip' => $client->current_address_zip_code
			];
		}
		if ($pdfId == 7) {
			$police = $client->policies()->first();
			$policyPeriod = '';
			if ($police && $police->term == 'monthly') {
				$policyPeriod = '(1) ONE';
			}
			if ($police && $police->term == '3_months') {
				$policyPeriod = '(3) THREE';
			}
			if ($police && $police->term == '6_months') {
				$policyPeriod = '(6) SIX';
			}
			if ($police && $police->term == 'year') {
				$policyPeriod = '(12) TWELVE';
			}
			return [
				'Customers Name' => $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name,
				'Name1' => $client->additional_insured_first_name . ' ' . $client->additional_insured_middle_name . ' ' . $client->additional_insured_last_name,
				'Address' => $client->current_address_line_1,
				'Address2' => $client->current_address_line_2,
				'City' => $client->current_address_address_city,
				'State' => $client->state ? $client->state->name : '',
				'Zip' => $client->current_address_zip_code,
				'Date' => date('m/d/Y'),
				'Insurance Company' => $police ? $police->company->company_name : '',
				'Policy' => $police ? $police->policy_number : '',
				'Company Phone Number' => $police ? $police->company->claims_phone : '',
				'Policy Period' => $police ? $police->term : '',
				'Effective Date' => $police ? $police->effective_date : '',
				'Down Payment' => $police ? $police->total_down_payment : '',
				'Payment(s)' => $police ? $police->monthly_payment : '',
				'Agent Name' => $client->agent ? $client->agent->name : '',
				'Customers Name' => $client->first_name . ' ' . $client->last_name,
				'BROKER FEE' => $police ? $police->broker_fee : '',
				'Polcy Period' => $policyPeriod,
				'Company Payment Address' => $police ? $police->company->payment_address : ''
			];
		}

		if ($pdfId == 8) {
			$police = $client->policies()->first();
			$policyPeriod = '';
			if ($police && $police->term == 'monthly') {
				$policyPeriod = 'UNO';
			}
			if ($police && $police->term == '3_months') {
				$policyPeriod = 'TRES';
			}
			if ($police && $police->term == '6_months') {
				$policyPeriod = 'SEIS';
			}
			if ($police && $police->term == 'year') {
				$policyPeriod = 'DOCE';
			}

			return [
				'Customers Name' => $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name,
				// 'Name1' => $client->additional_insured_first_name . ' ' . $client->additional_insured_middle_name . ' ' . $client->additional_insured_last_name,
				'Name or Company' => $client->additional_insured_first_name . ' ' . $client->additional_insured_middle_name . ' ' . $client->additional_insured_last_name,
				'Address' => $client->current_address_line_1,
				'Address2' => $client->current_address_line_2,
				'City' => $client->current_address_address_city,
				'State' => $client->state ? $client->state->name : '',
				'ZIP' => $client->current_address_zip_code,
				'Date' => date('m/d/Y'),
				'Insurance Company' => $police ? $police->company->company_name : '',
				'Policy' => $police ? $police->policy_number : '',
				'Insurance Company Address' => $police ? $police->company->payment_address : '',
				'Insurance Company Phone' => $police ? $police->company->claims_phone : '',
				'Effective Date' => $police ? $police->effective_date : '',
				'Down Payment' => $police ? $police->total_down_payment : '',
				'Agent name' => $client->agent ? $client->agent->name : '',
				'Monthly' => $police ? $police->monthly_payment : '',
				'Months' => $policyPeriod,
				'Months2' => $policyPeriod,
				'Agents name' => $client->agent ? $client->agent->name : '',
				'BROKER FEE' => $police ? $police->broker_fee : ''
			];
		}

		$additionalInsuredInfo = '';
		if ($client->policy_type_id == PolicyTypes::TYPE_COMMERCIAL) {
			$additionalInsuredInfo = $client->additional_insured_first_name . ' ' . 
				$client->additional_insured_middle_name . ' ' . 
				$client->additional_insured_last_name;
		}

		if ($pdfId == 9) {
			$police = $client->policies()->first();
			return [
				'Customer Name' => $client->first_name . ' ' . $client->last_name,
				'Customer Name - Business Name' => $additionalInsuredInfo,
				'Customer Name - Business Name2' => $client->business_name,
				'Address' => $client->current_address_line_1,
				'Address2' => $client->current_address_line_2,
				'Address3' => $client->current_address_address_city . ' ' . ($client->state ? $client->state->name . ' ' : '') . $client->current_address_zip_code,
				'Date' => date('m/d/Y'),
				'Customer Name1' => $client->first_name . ' ' . $client->last_name,
				'Customer Name2' => $client->additional_insured_first_name . ' ' . $client->additional_insured_last_name,
				'Company Total' => $police ? $police->company_total : '',
				'Monthly Payment' => $police ? $police->monthly_payment : '',
				'Total Down Payment' => $police ? $police->total_down_payment : '',
				'Agent Name' => $client->agent ? $client->agent->name : ''
			];
		}
		if ($pdfId == 10) {
			$police = $client->policies()->first();
			return [
				'Customer Name' => $client->first_name . ' ' . $client->last_name,
				'Customer Name - Business Name' => $additionalInsuredInfo,
				'Customer Name - Business Name2' => $client->business_name,
				'Address' => $client->current_address_line_1,
				'Address2' => $client->current_address_line_2,
				'Address3' => $client->current_address_address_city . ' ' . ($client->state ? $client->state->name. ' ' : '') . $client->current_address_zip_code,
				'Date' => date('m/d/Y'),
				'Customer Name1' => $client->first_name . ' ' . $client->last_name,
				'Customer Name2' => $client->additional_insured_first_name . ' ' . $client->additional_insured_last_name,
				'Company Total' => $police ? $police->company_total : '',
				'Monthly Payment' => $police ? $police->monthly_payment : '',
				'Total Down Payment' => $police ? $police->total_down_payment : '',
				'Agent Name' => $client->agent ? $client->agent->name : ''
			];
		}
		if ($pdfId == 11) {
			return [
				'First Name Primer Nombre' => $client->first_name,
				'Last Name Apellido' => $client->last_name,
				'Mobile Phone Numero de Celular' => $client->cell_phone,
				'Home other Number Inicio Otro Número' => $client->home_phone,
				'EmailCo' => $client->email_address,
				'Street Address Dirección' => $client->current_address_line_1 . ' ' . $client->current_address_line_2,
				'Zip Code Postal' => $client->current_address_zip_code,
                'City Ciudad' => $client->current_address_address_city,
                'Group1' => $client->getSex() !== 'MALE' ? 'Choice2' : 'Choice1',
			];
		} 
		if ($pdfId == "AUTOCLUB-1" || $pdfId == "OFFER") {
			PdfDownload::firstOrNew(['client_id' => $client->id, 'pdf_id' => $pdfId])->touch();

			$latinaCompany = Company::where('company_name', '=', 'DISCOUNT - LATIN AUTO CLUB')->first();
			$latinaReferralCompany = ReferralSource::where('referral_company' , 'DISCOUNT AUTO CLUB - LATIN AUTO CLUB')
				->orWhere('referral_company', 'LATIN AUTO CLUB')
				->first();

			switch ($client->autoClub()->first()->term){
				case "year":
					$term = '$249.95';
					break;
				case "6_months":
					$term = '$119.70';
					break;
				case "3_months":
					$term = '$179.55';
					break;
				case "monthly":
					$term = '$219.45';
					break;
			}

			return [
				'Agents Name' => $client->autoClub->referral_source_id ? $client->autoClub->referralSource->referral_company : ($latinaCompany ? $latinaCompany->company_name : ''),
				'Street Address' => $client->autoClub->referral_source_id ? 
					$client->autoClub->referralSource->referral_address_line_1 . ' ' . $client->autoClub->referralSource->referral_address_line_2 : 
					($latinaReferralCompany ? $latinaReferralCompany->referral_address_line_1 . ' ' . $latinaReferralCompany->referral_address_line_2 : ''),
				'City, State Zip' => $client->autoClub->referral_source_id ? 
					$client->autoClub->referralSource->referral_city . ' ' . 
					$client->autoClub->referralSource->states->name . ' ' . 
					$client->autoClub->referralSource->referral_zip : 
					(
						$latinaReferralCompany ?
							$latinaReferralCompany->referral_city . ' ' . 
							$latinaReferralCompany->states->name . ' ' . 
							$latinaReferralCompany->referral_zip : ''
					),
				'Phone Number' => $client->autoClub->referral_source_id ? $client->autoClub->referralSource->referral_cell : ($latinaCompany ? $latinaCompany->toll_free : ''),
				'Members Name' => $client->first_name . ' ' . $client->last_name,  
				'Member Street Adress' => $client->current_address_line_1 . ' ' . $client->current_address_line_2,
				'Member City, State Zip' => $client->current_address_address_city . ' ' . ($client->state ? $client->state->name . ' ' : "") . $client->current_address_zip_code,
				'Notice Date' => date('m/d/Y'),
				'Member Number' => $client->autoClub->member_id, 
				'Effective Date' => $client->autoClub->effective_date,
				'Expiration Date' => $client->autoClub->expiration_date,
				'Minimum Amount Due' =>  $client->autoClub->premium, 
				"Due Date" => $client->autoClub->expiration_date,
				"Year Full Pay" => $term ? $term : null,
				"Member Number" => $client->autoClub->member_id, 
				"Member Name" => $client->first_name . ' ' . $client->last_name,  
				"Member Full Address" => $client->current_address_line_1 . ' ' . $client->current_address_line_2 . ' ' . $client->current_address_address_city . ' ' . ($client->state ? $client->state->name . ' ' : '') . $client->current_address_zip_code,
				"Minimum Payment Due Pago Mínimo Requerido" => $client->autoClub->premium, 
				"Full Payment Pago Completo" =>  $term ? $term : null,
				"Payment Due Date Fecha Límite De Pago" => $client->autoClub->expiration_date,
				"Company Customer Number" =>  $client->autoClub->referral_source_id
			];
		}

		if ($pdfId == "LICENSE-1") {
			PdfDownload::firstOrNew(['client_id' => $client->id, 'pdf_id' => $pdfId])->touch();

			return [
				'Place of Birth' => strtoupper($client->getNationality()),
				'License No' => $client->licenseOnly ? $client->licenseOnly->license_number : '',
				'Last Name' => strtoupper($client->last_name),
				'Experation Date' => $client->licenseOnly ? $client->licenseOnly->expiration_date : '',
                'First Name, Mid' =>  strtoupper($client->first_name . ($client->middle_name ? ', ' . $client->middle_name : '')),
				'Address Street' => strtoupper($client->current_address_line_1),
				'City Street Zip' => strtoupper($client->current_address_address_city . ' ' . ($client->state ? $client->state->name . ' ' : '' ) . $client->current_address_zip_code),
                'Hight' => explode(' ', $client->getHeight())[0],
				'Eyes Color' => $client->getEyes(),
				'Date Of Birth' => $client->client_date_of_birth,
				'Sex' => $client->getSex(),
				'Class' => explode(' ', $client->getClass())[0],
				'Issued Date' => date('m/d/Y'),
                'Photo' => $client->licenseOnly ? ($client->licenseOnly->photo ?? '') : '',
                'Signature' => $client->licenseOnly ? ($client->licenseOnly->sign ?? '') : '',
			];
		}

		return [];
	}

    public static function generateForm($pdfId, $client_id)
	{
		$client = Clients::where('id', $client_id)->first();
		if (!$client) {
			return;
		}
		$filePath = public_path('/pdf/FAXBACK-' . $pdfId . '.pdf');
		if (!file_exists($filePath)) {
			dd('PDF DOES NOT EXIST');
		}
		
        // Fill form with data array
        $pdf = new Pdf($filePath);

        $formData = PDFData::getFormData($pdfId, $client_id);
        
        $pdf->fillForm($formData)
	        ->needAppearances();
        $saveFilePath = '/pdf/filled.pdf';
        if (!$pdf->saveAs(public_path($saveFilePath))) {
            dd($pdf->getError());
        }

        if ($pdfId == "LICENSE-1") {
            $com = new Command;

            $saveCmbinedPath = public_path('/pdf/filled1.pdf');

            if ($formData['Photo'] != "") {
                $saveImagePath = public_path("pdf/image.pdf"); //pdf image
                $imagePath = public_path($formData['Photo']);

                $operation = 'convert '. $imagePath .' '. $saveImagePath;

                exec($operation); // convert image to pdf

                $operation = "pdfjam --paper 'a4paper' --scale 0.16 --offset '-4.3cm 0cm' --outfile ". $saveImagePath ." ". $saveImagePath;

                exec($operation); // change image size

                $operation = public_path($saveFilePath) ." stamp ". $saveImagePath ." output ". $saveCmbinedPath;

                $com->setOperation($operation)->execute(); // merge pdf and photo
                unlink(public_path($saveFilePath));
                unlink($saveImagePath);
                rename($saveCmbinedPath, public_path($saveFilePath));
            }

            if ($formData['Signature']) {
                $saveSignaturePath = public_path("pdf/sign.pdf");
                $signaturePath = public_path($formData['Signature']);

                $operation = 'convert '. $signaturePath . ' ' . $saveSignaturePath;

                exec($operation); // convert signature to pdf

                $operation = "pdfjam --paper 'a4paper' --scale 0.13 --offset '-4.3cm -2.69cm' --outfile ". $saveSignaturePath ." ". $saveSignaturePath;
                exec($operation); // change image size

                $operation = public_path($saveFilePath) ." stamp ". $saveSignaturePath ." output ". $saveCmbinedPath;
                $com = new Command;
                $com->setOperation($operation)->execute(); // merge pdf and sign
                unlink(public_path($saveFilePath));
                unlink($saveSignaturePath);
                rename($saveCmbinedPath, public_path($saveFilePath));
            }
        }    
        return $saveFilePath;
    }
}
