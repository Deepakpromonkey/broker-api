<?php

namespace App\Http\Controllers\Api\V1\Shipment;

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
}
