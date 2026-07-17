<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [

        'uuid',

        'company_id',

        'created_by',

        'updated_by',

        'shipment_no',

        'pro_number',

        'carrier_name',

        'carrier_mc',

        'carrier_dot',

        'carrier_phone',

        'carrier_extension',

        'tracking_method',

        'country_code',

        'tracking_number',

        'truck_number',

        'trailer_number',

        'driver_phone_1',

        'driver_phone_2',

        'driver_phone_3',

        'driver_type',

        'team_load',

        'tracking_start_at',

        'notes',

        'status',
    ];

    protected $casts = [

        'team_load' => 'boolean',

        'tracking_start_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
