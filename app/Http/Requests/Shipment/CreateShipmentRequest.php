<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class CreateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'pro_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            // Carrier

            'carrier_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'carrier_mc' => [
                'nullable',
                'string',
                'max:100',
            ],

            'carrier_dot' => [
                'nullable',
                'string',
                'max:100',
            ],

            'carrier_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'carrier_extension' => [
                'nullable',
                'string',
                'max:20',
            ],

            // Tracking

            'tracking_method' => [
                'required',
                'in:driver_phone,eld,gps',
            ],

            'country_code' => [
                'nullable',
                'string',
                'max:10',
            ],

            'tracking_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Driver

            'truck_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'trailer_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'driver_phone_1' => [
                'nullable',
                'string',
                'max:20',
            ],

            'driver_phone_2' => [
                'nullable',
                'string',
                'max:20',
            ],

            'driver_phone_3' => [
                'nullable',
                'string',
                'max:20',
            ],

            'driver_type' => [
                'nullable',
                'in:company_driver,leased_owner_operator,independent_owner_operator,other_company_driver',
            ],

            'team_load' => [
                'boolean',
            ],

            'tracking_start_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
