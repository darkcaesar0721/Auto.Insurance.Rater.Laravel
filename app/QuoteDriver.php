<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteDriver extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'dob',
        'good_driver', 'good_student_age', 'license_no',
        'license_status', 'licensing_status', 'marital_status', 'sr_22',
        'spouse_is_driver', 'state',
        'wife_dob', 'wife_first_name', 'wife_license_status', 'wife_licensing_status', 'wife_sr_22',
    ];
}
