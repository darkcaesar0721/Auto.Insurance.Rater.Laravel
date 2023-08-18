<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWpImportUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_import_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('profile_picture')->nullable();      // Profile Picture
            $table->string('signature_picture')->nullable();    // Signature
            $table->string('membership_card')->nullable();      // Membership Card
            $table->string('membership_number')->nullable();    // Membership Number
            $table->string('license_number')->nullable();       // License Number
            $table->string('expires_on')->nullable();           // Expires On
            $table->string('given_names')->nullable();          // Given Names
            $table->string('surname')->nullable();              // Surname
            $table->string('address_line_1')->nullable();       // Address Line 1
            $table->string('address_line_2')->nullable();       // Address Line 2
            $table->string('nationality')->nullable();          // Nationality
            $table->integer('nationality_id')->nullable();
            $table->integer('height_id')->nullable();           // Height
            $table->string('eyes')->nullable();                 // Eyes
            $table->integer('eyes_id')->nullable();             // Eyes
            $table->integer('class_id')->nullable();            // Class
            $table->string('issued_on')->nullable();            // Issued On
            $table->integer('sex_id')->nullable();              // Sex
            $table->string('birth')->nullable();                // Date of Birth
            $table->string('email')->nullable();                // Email
            $table->string('phone')->nullable();                // Phone
            $table->string('order_date')->nullable();           // Order Date
            $table->string('order_type')->nullable();
            $table->string('plan')->nullable();
            $table->string('shipping_type')->nullable();
            $table->string('ordered')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::dropIfExists('wp_import_users');
    }
}
