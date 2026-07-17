<?php

namespace App\Services;

use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ShipmentService
{
    /**
     * Create Shipment
     */
    public function create(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            // Prevent duplicate Tracking Number within the same company
            if (
                ! empty($data['tracking_number']) &&
                Shipment::where('company_id', $user->company_id)
                    ->where('tracking_number', $data['tracking_number'])
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'tracking_number' => [
                        'Tracking number already exists.',
                    ],
                ]);
            }

            $shipment = Shipment::create([

                'uuid' => (string) Str::orderedUuid(),

                'company_id' => $user->company_id,

                'created_by' => $user->id,

                'updated_by' => $user->id,

                // Shipment
                'shipment_no' => $this->generateShipmentNumber(),

                'pro_number' => $data['pro_number'] ?? null,

                // Carrier
                'carrier_name' => isset($data['carrier_name'])
                    ? trim($data['carrier_name'])
                    : null,

                'carrier_mc' => isset($data['carrier_mc'])
                    ? strtoupper(trim($data['carrier_mc']))
                    : null,

                'carrier_dot' => isset($data['carrier_dot'])
                    ? strtoupper(trim($data['carrier_dot']))
                    : null,

                'carrier_phone' => $data['carrier_phone'] ?? null,

                'carrier_extension' => $data['carrier_extension'] ?? null,

                // Tracking
                'tracking_method' => $data['tracking_method'],

                'country_code' => $data['country_code'] ?? null,

                'tracking_number' => $data['tracking_number'] ?? null,

                // Driver
                'truck_number' => isset($data['truck_number'])
                    ? strtoupper(trim($data['truck_number']))
                    : null,

                'trailer_number' => isset($data['trailer_number'])
                    ? strtoupper(trim($data['trailer_number']))
                    : null,

                'driver_phone_1' => $data['driver_phone_1'] ?? null,

                'driver_phone_2' => $data['driver_phone_2'] ?? null,

                'driver_phone_3' => $data['driver_phone_3'] ?? null,

                'driver_type' => $data['driver_type'] ?? null,

                'team_load' => $data['team_load'] ?? false,

                // Tracking Start
                'tracking_start_at' => $data['tracking_start_at'] ?? null,

                // Notes
                'notes' => isset($data['notes'])
                    ? trim($data['notes'])
                    : null,

                // Status
                'status' => 'draft',

            ]);

            return $shipment->fresh();
        });
    }

    /**
     * Generate Shipment Number
     *
     * Example:
     * SHP-2026-000001
     */
    private function generateShipmentNumber(): string
    {
        $lastShipment = Shipment::latest('id')->first();

        $nextNumber = $lastShipment
            ? $lastShipment->id + 1
            : 1;

        return 'SHP-'.
            date('Y').
            '-'.
            str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
