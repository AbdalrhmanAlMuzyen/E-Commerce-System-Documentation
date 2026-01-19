<?php

namespace App\Http\Controllers;

use App\DTO\Dashboard\GetAverageRateBetweenOrdersDTO;
use App\DTO\Dashboard\GetCategoryProductStatisticsDTO;
use App\DTO\Dashboard\GetTopPurchasedProductsDTO;
use App\Http\Requests\Dashboard\GetAverageRateBetweenOrdersRequest;
use App\Http\Requests\Dashboard\GetCategoryProductStatisticsRequest;
use App\Http\Requests\Dashboard\GetTopPurchasedProductsRequest;
use App\Models\User;
use App\Service\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService=$dashboardService;
    }

    public function getUsersPurchaseStatistics()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getUsersPurchaseStatistics();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getCategoryProductStatistics(GetCategoryProductStatisticsRequest $request)
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getCategoryProductStatistics(GetCategoryProductStatisticsDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getOrdersStatistics()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getOrdersStatistics();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getTotalUsers()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getTotalUsers();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getOneTimeCustomers()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getOneTimeCustomers();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getRepeatCustomers()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getRepeatCustomers();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getSizeSalesOverview()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getSizeSalesOverview();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getAverageRateBetweenOrders(GetAverageRateBetweenOrdersRequest $request)
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getAverageRateBetweenOrders(GetAverageRateBetweenOrdersDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getInactiveUsers()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getInactiveUsers();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getTopPurchasedProducts(GetTopPurchasedProductsRequest $request)
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getTopPurchasedProducts(GetTopPurchasedProductsDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }

    public function getMostAddedButLeastOrdered()
    {
        $this->authorize("dashboard",User::class);
        $result = $this->dashboardService->getMostAddedButLeastOrdered();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);        
    }
}