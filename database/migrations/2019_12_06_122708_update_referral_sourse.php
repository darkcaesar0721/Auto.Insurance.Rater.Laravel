<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ReferralSource;

class UpdateReferralSourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $referral_sources = ReferralSource::get();
        Schema::table('referral_sources', function (Blueprint $table) {      // Add columns
            if (Schema::hasColumn('referral_sources', 'referral_address')) {
                $table->renameColumn('referral_address', 'referral_address_line_1');
            }
            if (Schema::hasColumn('referral_sources', 'referral_contact_name')) {
                $table->renameColumn('referral_contact_name', 'referral_first_name');
            }
            if (!Schema::hasColumn('referral_sources', 'referral_address_line_2')) {
                $table->string('referral_address_line_2')->nullable();
            }
            if (!Schema::hasColumn('referral_sources', 'referral_last_name')) {
                $table->string('referral_last_name')->nullable();
            }
            if (!Schema::hasColumn('referral_sources', 'referral_tax_id')) {
                $table->string('referral_tax_id')->nullable();
            if (!Schema::hasColumn('referral_sources', 'referral_license')) {
            }
                $table->string('referral_license')->nullable();
            }
        });
        foreach ($referral_sources as $sourse) {
            $entity = ReferralSource::where('id', $sourse->id)->first();
            if ($entity) {
                $names = explode(' ', $sourse->referral_contact_name, 2);
                if (count($names) === 2) {
                    $entity->referral_first_name = trim($names[0]);
                    $entity->referral_last_name = trim($names[1]);
                    $entity->update();
                } 
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $referral_sources = ReferralSource::get();
        Schema::table('referral_sources', function (Blueprint $table) {      // Add columns
            if (Schema::hasColumn('referral_sources', 'referral_address_line_1')) {
                $table->renameColumn('referral_address_line_1', 'referral_address');
            }
            if (Schema::hasColumn('referral_sources', 'referral_first_name')) {
                $table->renameColumn('referral_first_name', 'referral_contact_name');
            }
            if (Schema::hasColumn('referral_sources', 'referral_last_name')) {
                $table->dropColumn('referral_last_name');
            }
            if (Schema::hasColumn('referral_sources', 'referral_address_line_2')) {
                $table->dropColumn('referral_address_line_2');
            }
            if (Schema::hasColumn('referral_sources', 'referral_tax_id')) {
                $table->dropColumn('referral_tax_id');
            }
            if (Schema::hasColumn('referral_sources', 'referral_tax_id')) {
                $table->dropColumn('referral_license');
            }
        });
        foreach ($referral_sources as $sourse) {
            $entity = ReferralSource::where('id', $sourse->id)->first();
            if ($entity) {
                $entity->referral_address = trim($sourse->referral_address_line_1 . ' ' . $sourse->referral_address_line_2);
                $entity->referral_contact_name = trim($sourse->referral_first_name . ' ' . $sourse->referral_last_name); 
                $entity->update();
            }
        }
    }
}
