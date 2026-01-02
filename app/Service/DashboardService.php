<?php

namespace App\Service;

use App\DTO\Dashboard\GetAverageRateBetweenOrdersDTO;
use App\DTO\Dashboard\GetCategoryProductStatisticsDTO;
use App\DTO\Dashboard\GetTopPurchasedProductsDTO;
use App\Repository\DashboardRepository;
use App\Repository\AuthenticationRepository;
use App\Repository\CategoryRepository;
use App\ReturnTrait;

class DashboardService
{
    use ReturnTrait;
    protected $dashboardRepository;
    protected $authenticationRepository;
    protected $categoryRepository;
    public function __construct(DashboardRepository $dashboardRepository,AuthenticationRepository $authenticationRepository,CategoryRepository $categoryRepository)
    {
        $this->dashboardRepository=$dashboardRepository;
        $this->authenticationRepository=$authenticationRepository;
        $this->categoryRepository=$categoryRepository;
    } 

    public function getUsersPurchaseStatistics()
    {
        try {
            $data = $this->dashboardRepository->getUsersPurchaseStatistics();
            return $this->success(true, 'Users purchase statistics', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getCategoryProductStatistics(GetCategoryProductStatisticsDTO $dto)
    {
        try {
            $category=$this->categoryRepository->findCategoryByID($dto->category_id);
            if(!$category)
            {
                return $this->error(false,"Category not found",null,404);
            }
            $data = $this->dashboardRepository->getCategoryProductStatistics($category);
            return $this->success(true, 'Category product statistics', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getOrdersStatistics()
    {
        try {
            $data = $this->dashboardRepository->getOrdersStatistics();
            return $this->success(true, 'Orders statistics', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getTotalUsers()
    {
        try {
            $data = $this->dashboardRepository->getTotalUsers();
            return $this->success(true, 'Total users', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getOneTimeCustomers()
    {
        try {
            $count = $this->dashboardRepository->getOneTimeCustomers();
            return $this->success(true, 'One time customers', $count);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getRepeatCustomers()
    {
        try {
            $count = $this->dashboardRepository->getRepeatCustomers();
            return $this->success(true, 'Repeat customers', $count);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getSizeSalesOverview()
    {
        try {
            $data = $this->dashboardRepository->getSizeSalesOverview();
            return $this->success(true, 'Size sales overview', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getAverageRateBetweenOrders(GetAverageRateBetweenOrdersDTO $dto)
    {
        try {
            $user=$this->authenticationRepository->findUserByID($dto->user_id);
            if(!$user)
            {
                return $this->error(false,"user not found",null,404);
            }            
            $data = $this->dashboardRepository->getAverageRateBetweenOrders($user);
            return $this->success(true, 'Average days between orders', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getInactiveUsers()
    {
        try {
            $data = $this->dashboardRepository->getInactiveUsers();
            return $this->success(true, 'Inactive users', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getTopPurchasedProducts(GetTopPurchasedProductsDTO $dto)
    {
        try {
            $user=$this->authenticationRepository->findUserByID($dto->user_id);
            if(!$user)
            {
                return $this->error(false,"user not found",null,404);
            }
            $data = $this->dashboardRepository->getTopPurchasedProducts($user);
            return $this->success(true, 'Top purchased products', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }

    public function getMostAddedButLeastOrdered()
    {
        try {
            $data = $this->dashboardRepository->getMostAddedButLeastOrdered();
            return $this->success(true, 'Most added but least ordered products', $data);
        } catch (\Exception $e) {
            return $this->error(false, $e->getMessage());
        }
    }
}
