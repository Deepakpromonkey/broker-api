<?php

namespace App\Http\Controllers\Api\V1\Shipment;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Shipment\CreateShipmentRequest;
use App\Http\Resources\ShipmentResource;
use App\Services\ShipmentService;

class ShipmentController extends BaseController
{
    public function __construct(
        protected ShipmentService $shipmentService
    ) {}

    public function store(CreateShipmentRequest $request)
    {
        $shipment = $this->shipmentService->create(
            $request->validated(),
            auth()->user()
        );

        return $this->success(
            new ShipmentResource($shipment),
            'Shipment created successfully.',
            201
        );
    }

   public function addStops(Request $request, $uuid)
    {
        $request->validate([
            'stops_data' => 'required|string',
        ]);

        $stopsArray = json_decode($request->stops_data, true);

        if (!is_array($stopsArray) || count($stopsArray) < 2) {
            return $this->error('Invalid stops data. Minimum 2 stops required.', 422);
        }

        $shipment = \App\Models\Shipment::where('uuid', $uuid)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        $updatedShipment = $this->shipmentService->addStops($shipment, $stopsArray, $request);

        return $this->success(
            $updatedShipment,
            'Trip Sheet stops saved successfully.',
            200
        );
    }
}
