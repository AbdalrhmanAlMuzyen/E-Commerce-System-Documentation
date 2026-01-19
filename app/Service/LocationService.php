<?php

namespace App\Service;

use App\DTO\Location\CreateLocationDTO;
use App\DTO\Location\DeleteLocationDTO;
use App\DTO\Location\UpdateLocationDTO;
use App\Repository\LocationRepository;
use App\ReturnTrait;

class LocationService{
    use ReturnTrait;
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository=$locationRepository;
    }

    public function createLocation(CreateLocationDTO $dto)
    {
        try{
            $location=$this->locationRepository->createLocation($dto);
            return $this->success(true,"location created successfully",$location,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function getLocations()
    {
        try{
            $locations=$this->locationRepository->getLocations();
            if($locations->isEmpty())
            {   
                return $this->error(false,"locations not found",[],404);
            }
            return $this->success(true,"locations retrieved successfully",$locations,200);
        } 
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());           
        }
    }

    public function deleteLocation(DeleteLocationDTO $dto)
    {
        try{
            $location=$this->locationRepository->findLocationByID($dto->location_id);

            if(!$location)
            {
                return $this->error(false,"location not found",null,404);
            }

            $this->locationRepository->deleteLocation($location);
            return $this->success(true,"location deleted successfully");
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());           
        }
    }

    public function updateLocation(UpdateLocationDTO $dto)
    {
        try{
            $location=$this->locationRepository->findLocationByID($dto->location_id);

            if(!$location)
            {
                return $this->error(false,"loction not found",null,404);
            }

            $data=collect([
                "name"=>$dto->name,
                "delivery_fe"=>$dto->delivery_fee
            ])->filter(function($value){
                return !is_null($value);
            })->toArray();

            if(empty($data))
            {
                return $this->error(false, 'No data to update', null, 400);
            }

            $this->locationRepository->updateLocation($location,$data);
            return $this->success(true,"location updated successfully",$location->fresh());
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());           
        }
    }
}