<?php
namespace App\DTO\Authentication;

class RegisterDTO{

    public string $first_name;
    public string $last_name;
    public string $email;
    public string $passowrd;
    public int $location_id;
    public string $address_details;
    public int $building_number;
    public string $device_id;

    public function __construct(string $first_name,string $last_name,string $email , string $passowrd,int $location_id,string $address_details,string $building_number,string $device_id)
    {
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->email=$email;
        $this->passowrd=$passowrd;
        $this->location_id=$location_id;
        $this->address_details=$address_details;
        $this->building_number=$building_number;
        $this->device_id=$device_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("first_name"),$request->input("last_name"),$request->input("email"),$request->input("password"),$request->input("location_id"),$request->input("address_details"),$request->input("building_number"),$request->input("device_id"));
    }
}  