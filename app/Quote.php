<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Quote extends Model
{
    use Notifiable;

    protected $fillable = [
        'email', 'address', 'phone', 'total_quoted_amount', 'quote_company', 'zip', 'coverage',
        'card_authorized', 'email_verified', 'full_name', 'payment_type', 'agent_name', 'agent_no',
        'note'
    ];

    protected $dates = ['created_at'];

    protected $appends = ['hash_id'];

    public function getHashIdAttribute() {
        return Hasher::encode($this->id);
    }

    public function getFilesCountAttribute() {
        $directory = "auto/$this->hash_id";
        return count(Storage::allFiles($directory));
    }

    public function getFilesAttribute() {
        $directory = "auto/$this->hash_id";
        $drivers = Storage::allFiles($directory . '/drivers');
        $vehiclePhotos = Storage::allFiles($directory . '/vehicle-photos');
        $vehiclesRegistrations = Storage::allFiles($directory . '/vehicles-registrations');
        return [
            'Drivers' => $drivers,
            'Vehicles Registrations' => $vehiclesRegistrations,
            'Vehicle Photos' => $vehiclePhotos
        ];
    }

    public function vehicles() {
        return $this->hasMany(QuoteVehicle::class);
    }

    public function drivers() {
        return $this->hasMany(QuoteDriver::class);
    }
}
