<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\CompanyList;

class CreateCompanyListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $companyList = [
            'Allstate',
            'ASI Insurance',
            'Aspire Insurance',
            'Bridger Auto',
            'CIG - California Capital',
            'Eagle West Ins Co',
            'Cincinnati',
            'CSE Insurance Group',
            'Drive Insurance',
            'Encompass',
            'First American P&C',
            'Grange Insurance Association',
            'Infinity RSVP',
            'Infinity RSVP',
            'MAPFRE',
            'Merced Property & Casualty',
            'Mercury',
            'Metlife',
            'Nationwide',
            'Oregon Mutual',
            'Safeco',
            'Stillwater Insurance Group',
            'Travelers',
            'All Star Ocean Harbor Named Op',
            'Alliance United - Gold',
            'Anchor Gemini',
            'Assigned Risk',
            'Bankers Standard Insurance Co',
            'Bristol West Ins.',
            'Carnegie Centurion',
            'Chubb Masterpiece',
            'Dairyland Insurance',
            'Financial Indemnity',
            'Garrison P&C Inc. Co.',
            'Legacy - Western Home Ins.',
            'Multi-State Insurance',
            'National General',
            'Nations Insurance Company',
            'Pronto General',
            'Reliant General',
            'RMIS Insurance',
            'Safeway Insurance Company',
            'Sun Coast Insurance',
            'Tokio Marine',
            'Western General',
            'Western United',
            'Workmens Auto Insurance Company',
            'CIG - California Capital',
            'Eagle West Ins Co',
            'Cincinnati',
            'CSE Insurance Group',
            'Encompass',
            'First American Specialty',
            'Grange Insurance Association',
            'Kemper Insurance',
            'MAPFRE Insurance Co',
            'Merced Property & Casualty',
            'Mercury Insurance',
            'Metlife',
            'NatGen Premier',
            'Nationwide',
            'Nationwide Private Client',
            'Oregon Mutual',
            'Pacific Specialty',
            'QBE',
            'Safeco',
            'Stillwater Insurance Group',
            'Travelers',
            'Universal North America'
        ];
        foreach ($companyList as $company) {
            CompanyList::create(
                ['name' => $company]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_lists');
    }
}
