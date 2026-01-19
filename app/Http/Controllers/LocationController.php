<?php

namespace App\Http\Controllers;

use App\DTO\Location\CreateLocationDTO;
use App\DTO\Location\DeleteLocationDTO;
use App\DTO\Location\UpdateLocationDTO;
use App\Http\Requests\Location\CreateLocationRequest;
use App\Http\Requests\Location\DeleteLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Location;
use App\Service\LocationService;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService=$locationService;
    }

    public function createLocation(CreateLocationRequest $request)
    {
        $this->authorize("create",Location::class);
        $result = $this->locationService->createLocation(CreateLocationDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }

    public function getLocations()
    {
        $result = $this->locationService->getLocations();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }
    
    public function deleteLocation(DeleteLocationRequest $request)
    {
        $this->authorize("delete",Location::class);
        $result = $this->locationService->deleteLocation(DeleteLocationDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        $this->authorize("update",Location::class);
        $result = $this->locationService->updateLocation(UpdateLocationDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }
}