<?php

namespace App\Service;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\DeleteCategoryDTO;
use App\DTO\Category\ShowCategoryDTO;
use App\Models\Category;
use App\Repository\CategoryRepository;
use App\ReturnTrait;

class CategoryService{
    protected $categoryRepository;
    use ReturnTrait;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository=$categoryRepository;
    }

    public function createCategory(CreateCategoryDTO $dto)
    {
        try{
            $category=$this->categoryRepository->createCategory($dto);

            return $this->success(true,'Categories created successfully',$category,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteCategory(DeleteCategoryDTO $dto)
    {
        try{
            $category=$this->categoryRepository->findCategoryByID($dto->category_id);

            if(!$category)
            {
                return $this->error(false, 'Category not found', 404);
            } 
            
            $this->categoryRepository->deleteCategory($category);
            return $this->success(true,'Categories deleted successfully',null);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }         
    }

    public function getCategories()
    {
        try {
            $categories = $this->categoryRepository->getCategories();

            if ($categories->isEmpty()) {
                return $this->error(false,'No categories found',null,404);
            }

            return $this->success(true,'Categories retrieved successfully',$categories,200);

        } catch (\Exception $e) {
            return $this->error(false,$e->getMessage());
        }
    }

    public function showCategory(ShowCategoryDTO $dto)
    {
        try{
            $category=$this->categoryRepository->findCategoryByID($dto->category_id);
                                                                                             
            if(!$category)
            {
                return $this->error(false, 'Category not found', 404);
            }

            $products=$this->categoryRepository->showCategory($category);

            if ($products->isEmpty()) {
                return $this->error(false, 'No products found for this category', [], 200);
            }   
            
            return $this->success(true,"Category products retrieved successfully",$products);  
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }


    





}