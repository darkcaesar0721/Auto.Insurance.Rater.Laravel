<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ClientTypes;
use Illuminate\Http\Request;
use App\Clients;
use Auth;
use App\PolicyTypes;

class ReportsController extends Controller
{   
    public function index() {
        return view('back-office.reports');
    }

    private static function getBaseQuery(Request $request) {
        if($request->policy_type == PolicyTypes::TYPE_COMMERCIAL) {
            $query = Clients::where('client_type_id', ClientTypes::TYPE_CLIENT)
                ->where('clients.policy_type_id', $request->policy_type)
                ->leftJoin('client_policies as cp', 'cp.client_id', 'clients.id')
                ->leftJoin('client_license_only as clo', 'clo.client_id', 'clients.id')
                ->leftJoin('client_auto_club as cac', 'cac.client_id', 'clients.id')
                ->leftJoin('client_numbers', 'client_numbers.client_id', '=', 'clients.id');
        }
        else {
            $query = Clients::where('client_type_id', ClientTypes::TYPE_CLIENT)
                ->where('clients.policy_type_id', $request->policy_type)
                //->where('clients.auto_club', $request->auto_club_checkbox) 
                //->where('clients.auto_club_license_only', $request->license_checkbox)
                ->leftJoin('client_policies as cp', 'cp.client_id', 'clients.id')
                ->leftJoin('client_license_only as clo', 'clo.client_id', 'clients.id')
                ->leftJoin('client_auto_club as cac', 'cac.client_id', 'clients.id')
                ->leftJoin('client_numbers', 'client_numbers.client_id', '=', 'clients.id');
        }
       
        if ($request->policy_type == PolicyTypes::TYPE_LICENSE_ONLY) {
            if ($referralId = $request->get('referral')) {
                $query->where('clo.referral_source_id', $referralId);
            }

            if ($effectiveDateFrom = $request->get('effective_date_from')) {
                $query->where('clo.effective_date', '>=', date('Y-m-d', strtotime($effectiveDateFrom)));
            }
            if ($effectiveDateTo = $request->get('effective_date_to')) {
                $query->where('clo.effective_date', '<=', date('Y-m-d', strtotime($effectiveDateTo)));
            }

            if ($expirationDateFrom = $request->get('expiration_date_from')) {
                $query->where('clo.expiration_date', '>=', date('Y-m-d', strtotime($expirationDateFrom)));
            }
            if ($expirationDateTo = $request->get('expiration_date_to')) {
                $query->where('clo.expiration_date', '<=', date('Y-m-d', strtotime($expirationDateTo)));
            }
        }
        elseif ($request->policy_type == PolicyTypes::TYPE_AUTO_CLUB) {
            $query->where('clients.auto_club_license_only', $request->license_checkbox);
            if ($referralId = $request->get('referral')) {
                $query->where('cac.referral_source_id', $referralId);
            }

            if ($effectiveDateFrom = $request->get('effective_date_from')) {
                $query->where('cac.effective_date', '>=', date('Y-m-d', strtotime($effectiveDateFrom)));
            }
            if ($effectiveDateTo = $request->get('effective_date_to')) {
                $query->where('cac.effective_date', '<=', date('Y-m-d', strtotime($effectiveDateTo)));
            }

            if ($expirationDateFrom = $request->get('expiration_date_from')) {
                $query->where('cac.expiration_date', '>=', date('Y-m-d', strtotime($expirationDateFrom)));
            }
            if ($expirationDateTo = $request->get('expiration_date_to')) {
                $query->where('cac.expiration_date', '<=', date('Y-m-d', strtotime($expirationDateTo)));
            }
        }
        else {
            $query->where('clients.auto_club', $request->auto_club_checkbox);
            $query->where('clients.auto_club_license_only', $request->license_checkbox);
            if ($companyId = $request->get('company')) {
                $query->where('cp.company_list_id', $companyId);
            }
            
            if ($referralId = $request->get('referral')) {
                $query->where('cp.referral_source_id', $referralId);
            }

            if ($effectiveDateFrom = $request->get('effective_date_from')) {
                $query->where('cp.effective_date', '>=', date('Y-m-d', strtotime($effectiveDateFrom)));
            }
            if ($effectiveDateTo = $request->get('effective_date_to')) {
                $query->where('cp.effective_date', '<=', date('Y-m-d', strtotime($effectiveDateTo)));
            }

            if ($expirationDateFrom = $request->get('expiration_date_from')) {
                $query->where('cp.expiration_date', '>=', date('Y-m-d', strtotime($expirationDateFrom)));
            }
            if ($expirationDateTo = $request->get('expiration_date_to')) {
                $query->where('cp.expiration_date', '<=', date('Y-m-d', strtotime($expirationDateTo)));
            }
            if ((int) $request->get('is_endorsement')) {
                $query->where('cp.is_endorsement', 1);
            }
        }

        if ($request->license_checkbox && ($request->policy_type == PolicyTypes::TYPE_AUTO_CLUB || $request->policy_type == PolicyTypes::TYPE_PERSONAL)) {
            
            $query->where('clients.auto_club_license_only', $request->license_checkbox);

            if ($referralId = $request->get('referral')) {
                $query->orWhere('clo.referral_source_id', $referralId);
            }

            if ($effectiveDateFrom = $request->get('effective_date_from')) {
                $query->orWhere('clo.effective_date', '>=', date('Y-m-d', strtotime($effectiveDateFrom)));
            }
            if ($effectiveDateTo = $request->get('effective_date_to')) {
                $query->orWhere('clo.effective_date', '<=', date('Y-m-d', strtotime($effectiveDateTo)));
            }

            if ($expirationDateFrom = $request->get('expiration_date_from')) {
                $query->orWhere('clo.expiration_date', '>=', date('Y-m-d', strtotime($expirationDateFrom)));
            }
            if ($expirationDateTo = $request->get('expiration_date_to')) {
                $query->orWhere('clo.expiration_date', '<=', date('Y-m-d', strtotime($expirationDateTo)));
            }
        }
        
        if ($request->auto_club_checkbox && $request->policy_type == PolicyTypes::TYPE_PERSONAL) {
            
            $query->where('clients.auto_club', ($request->auto_club_checkbox));

            if ($referralId = $request->get('referral')) {
                $query->orWhere('cac.referral_source_id', $referralId);
            }

            if ($effectiveDateFrom = $request->get('effective_date_from')) {
                $query->orWhere('cac.effective_date', '>=', date('Y-m-d', strtotime($effectiveDateFrom)));
            }
            if ($effectiveDateTo = $request->get('effective_date_to')) {
                $query->orWhere('cac.effective_date', '<=', date('Y-m-d', strtotime($effectiveDateTo)));
            }

            if ($expirationDateFrom = $request->get('expiration_date_from')) {
                $query->orWhere('cac.expiration_date', '>=', date('Y-m-d', strtotime($expirationDateFrom)));
            }
            if ($expirationDateTo = $request->get('expiration_date_to')) {
                $query->orWhere('cac.expiration_date', '<=', date('Y-m-d', strtotime($expirationDateTo)));
            }
        }

        return $query;
    }

    public function reportData(Request $request)
    {
    	$data = self::getBaseQuery($request)->select(
			\DB::raw('
				count(cp.client_id) as policies_number,
				sum(IFNULL(cp.premium, 0) + IFNULL(clo.price, 0) + IFNULL(cac.premium, 0)) as premium_sum,
				sum(IFNULL(cp.amount, 0) + IFNULL(cac.referral_amount, 0)) as amount_sum,
				sum(IFNULL(cp.broker_fee, 0) + IFNULL(cac.co_fees, 0)) as broker_fee_sum,
				sum(IFNULL(cp.company_down_payment, 0) + IFNULL(cac.company_total, 0) + IFNULL(clo.total_cost, 0)) as down_payment_sum,
                sum(IFNULL(clo.ship_fee, 0)) as ship_fee_sum
			')
		)->first();

        switch ($request->policy_type) {
            case PolicyTypes::TYPE_AUTO_CLUB:
                $tableData = self::getBaseQuery($request)->select(
                    \DB::raw('
                        cac.effective_date,
                        cac.expiration_date,
                        
                        client_numbers.number,
                        clients.first_name,
                        clients.last_name,
                        
                        cac.member_id as policy_number,
                        cac.premium as premium,
                        cac.company_total as company_down_payment,
                        cac.co_fees as broker_fee,
                        cac.referral_amount as amount,

                        clo.effective_date as l_effective_date,
                        clo.expiration_date as l_expiration_date,
                        clo.license_number as l_policy_number,
                        clo.price as l_premium,
                        clo.total_cost as l_down_payment,
                        clo.ship_fee as l_broker_fee
                    ')
                )
                ->get();
                break;
            case PolicyTypes::TYPE_LICENSE_ONLY:
                $tableData = self::getBaseQuery($request)->select(
                    \DB::raw('
                        clo.effective_date,
                        clo.expiration_date,
                        
                        client_numbers.number,
                        clients.first_name,
                        clients.last_name,
                        
                        clo.license_number as policy_number,
                        clo.price as premium,
                        clo.total_cost as company_down_payment,
                        clo.ship_fee as broker_fee
                    ')
                )
                ->get();
                break;
            default:
                $tableData = self::getBaseQuery($request)->select(
                    \DB::raw('
                        cp.effective_date,
                        cp.expiration_date,
                        client_numbers.number,
                        clients.first_name,
                        clients.last_name,
                        cp.policy_number,
                        cp.premium,
                        cp.company_down_payment,
                        cp.broker_fee,
                        cp.amount,
                        cac.effective_date as ac_effective_date,
                        cac.expiration_date as ac_expiration_date,
                        cac.member_id as ac_policy_number,
                        cac.premium as ac_premium,
                        cac.company_total as ac_down_payment,
                        cac.co_fees as ac_broker_fee,
                        cac.referral_amount as ac_referral_fee,
                        clo.effective_date as l_effective_date,
                        clo.expiration_date as l_expiration_date,
                        clo.license_number as l_policy_number,
                        clo.price as l_premium,
                        clo.total_cost as l_down_payment,
                        clo.ship_fee as l_broker_fee
                    ')
                )
                ->get();
                break;
        }


    	return response()->json([
    		'data' => $data,
    		'tableData' => $tableData
    	]);
    }
}
