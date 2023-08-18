<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ClientCountry;
use App\ClientHeight;
use App\ClientEyes;
use App\ClientSex;
use App\ClientClass;

class CreateUserCharacteristicsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country');
            $table->timestamps();
        });
        Schema::create('client_heights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('height');
            $table->timestamps();
        });
        Schema::create('client_eyes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eyes');
            $table->timestamps();
        });
        Schema::create('client_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class');
            $table->timestamps();
        });
        Schema::create('client_sex', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sex');
            $table->timestamps();
        });
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

        foreach ($countries as $country) {
            ClientCountry::firstOrCreate([
                'country' => $country
            ]);
        }

        $heights = array("4.0\" - 121.92 CM","4.1\" - 124.46 CM","4.2\" - 127.00 CM","4.3\" - 129.54 CM","4.4\" - 132.08 CM","4.5\" - 134.62 CM","4.6\" - 137.16 CM","4.7\" - 139.70 CM","4.8\" - 142.24 CM","4.9\" - 144.78 CM","4.10\" - 147.32 CM","4.11\" - 149.86 CM","5.0\" - 152.40 CM","5.1\" - 154.94 CM","5.2\" - 157.48 CM","5.3\" - 160.02 CM","5.4\" - 162.56 CM","5.5\" - 165.10 CM","5.6\" - 167.64 CM","5.7\" - 170.18 CM","5.8\" - 172.72 CM","5.9\" - 175.26 CM","5.10\" - 177.80 CM","5.11\" - 180.34 CM","5.11\" - 180.34 CM", "6.0\" - 182.88 CM","6.1\" - 185.42 CM", "6.2\" - 187.96 CM", "6.3\" - 190.50 CM","6.4\" - 193.04 CM","6.5\" - 195.58 CM","6.6\" - 198.12 CM","6.7\" - 200.66 CM","6.8\" - 203.20 CM");

        foreach ($heights as $height) {
            ClientHeight::firstOrCreate([
                'height' => $height
            ]);
        }

        $eyes = array("BROWN", "GREEN", "BLUE", "HAZEL", "GRAY", "BLACK", "OTHER");

        foreach ($eyes as $eyesInput) {
            ClientEyes::firstOrCreate([
                'eyes' => $eyesInput
            ]);
        }

        $sex = array("MALE", "FEMALE");
        
        foreach ($sex as $sexInput) {
            ClientSex::firstOrCreate([
                'sex' => $sexInput
            ]);
        } 

        $classes = array("A - MOTORCYCLE", "B - PASSENGER CAR", "C - VEHICLE OVER 7700 POUNDS (4266 KG)", "D - VEHICLE OVER 8 SEATS", "E - VEHICLE OF B,C,D WITH OTHER THEN A LIGHT TRAILER");

        foreach ($classes as $class) {
            ClientClass::firstOrCreate([
                'class' => $class
            ]);
        } 
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
