<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Company;

class GetRidOfCompanyListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companyList = DB::table('company_lists')->get();
        foreach ($companyList as $c) {
            // check if company already exists
            $companyExists = Company::where('company_name', $c->name)->first();
            if (!$companyExists) {
                Company::create([
                    'company_name' => $c->name
                ]);
            }
        }
        Schema::dropIfExists('company_lists');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
