<?php

namespace App\Http\Controllers;

use App\DTO\Color\CreateColorDTO;
use App\DTO\Color\DeleteColorDTO;
use App\Http\Requests\Color\CreateColorRequest;
use App\Http\Requests\Color\DeleteColorRequest;
use App\Models\Color;
use App\Service\ColorService;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService=$colorService;
    }

    public function createColor(CreateColorRequest $request)
    {
        $this->authorize("create",Color::class);
        $result=$this->colorService->createColor(CreateColorDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function deleteColor(DeleteColorRequest $request)
    {
        $this->authorize("delete",Color::class);
        $result=$this->colorService->deleteColor(DeleteColorDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getColors()
    {
        $result=$this->colorService->getColors();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);    
    }
}