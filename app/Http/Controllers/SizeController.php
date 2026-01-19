<?php

namespace App\Http\Controllers;

use App\DTO\Size\CreateSizeDTO;
use App\DTO\Size\DeleteSizeDTO;
use App\Http\Requests\Size\CreateSizeRequest;
use App\Http\Requests\Size\DeleteSizeRequest;
use App\Models\Size;
use App\Service\SizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService=$sizeService;
    }

    public function createSize(CreateSizeRequest $request)
    {
        $this->authorize("create",Size::class);
        $result=$this->sizeService->createSize(CreateSizeDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function deleteSize(DeleteSizeRequest $request)
    {
        $this->authorize("delete",Size::class);
        $result=$this->sizeService->deleteSize(DeleteSizeDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getSizes()
    {
        $result=$this->sizeService->getSizes();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);         
    }

    
}
