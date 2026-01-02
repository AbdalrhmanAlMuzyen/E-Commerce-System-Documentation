<?php

namespace App\Http\Controllers;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\DeleteCategoryDTO;
use App\DTO\Category\ShowCategoryDTO;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\DeleteCategoryRequest;
use App\Http\Requests\Category\ShowCategoryRequest;
use App\Models\Category;
use App\Service\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService=$categoryService;
    }

    public function createCategory(CreateCategoryRequest $request)
    {
        $this->authorize("create",Category::class);
        $result=$this->categoryService->createCategory(CreateCategoryDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function deleteCategory(DeleteCategoryRequest $request)
    {
        $this->authorize("delete",Category::class);
        $result=$this->categoryService->deleteCategory(DeleteCategoryDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function getCategories()
    {
        $result=$this->categoryService->getCategories();
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    public function showCategory(ShowCategoryRequest $request)
    {
        $result=$this->categoryService->showCategory(ShowCategoryDTO::FormRequest($request));
        return response(["success"=>$result["success"],"message"=>$result["message"],"data"=>$result["data"]],$result["code"]);
    }

    
}
