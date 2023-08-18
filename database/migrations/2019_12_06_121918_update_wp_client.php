<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWpClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wp_import_users', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('wp_import_users', 'agent_first_name')) {
                $table->string('agent_first_name')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_last_name')) {
                $table->string('agent_last_name')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_address_one')) {
                $table->string('agent_address_one')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_address_two')) {
                $table->string('agent_address_two')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_city')) {
                $table->string('agent_city')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_state')) {
                $table->string('agent_state')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_zip')) {
                $table->string('agent_zip')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_tax_id')) {
                $table->string('agent_tax_id')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_phone')) {
                $table->string('agent_phone')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'agent_fax')) {
                $table->string('agent_fax')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_import_users', function (Blueprint $table) {
            if (Schema::hasColumn('wp_import_users', 'agent_first_name')) {
                $table->dropColumn('agent_first_name');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_last_name')) {
                $table->dropColumn('agent_last_name');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_address_one')) {
                $table->dropColumn('agent_address_one');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_address_two')) {
                $table->dropColumn('agent_address_two');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_city')) {
                $table->dropColumn('agent_city');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_state')) {
                $table->dropColumn('agent_state');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_zip')) {
                $table->dropColumn('agent_zip');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_tax_id')) {
                $table->dropColumn('agent_tax_id');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_phone')) {
                $table->dropColumn('agent_phone');
            }
            if (Schema::hasColumn('wp_import_users', 'agent_fax')) {
                $table->dropColumn('agent_fax');
            }
        });
    }
}
