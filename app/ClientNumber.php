<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Clients;

class ClientNumber extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'number',
		'client_id'
	];

	public static function generateClientNo($addPrefix = false) {
        $prefix = (new \DateTime())->format('Y') . '-';
        $index = 100000;
        
        if(\Schema::hasTable('client_numbers')) {
        	$max = ClientNumber::withTrashed()
        				->select('number')
        				->where('number', 'like', $prefix . '%')
                        ->where('added_manually', false)
        				->orderBy('number', 'desc')
        				->first();
        }
        else {
        	$max = Clients::select('client_number')
        				->where('client_number', 'like', $prefix . '%')
        				->orderBy('client_number', 'desc')
        				->first();
        } 

        if ($max) {
        	$index = intval(str_replace($prefix, '', $max->number ?? $max->client_number));
        }
        
        do {
            $client_number = $prefix . str_pad($index, 6, '0', STR_PAD_LEFT);   
            $number = ClientNumber::withTrashed()->where('number', $client_number)->first();
            if ($number) {
                $index++;
            }
            else {
                break;
            }
        } while (true);
        return ($addPrefix ? $prefix : '') . str_pad($index, 6, '0', STR_PAD_LEFT);
    }
}
