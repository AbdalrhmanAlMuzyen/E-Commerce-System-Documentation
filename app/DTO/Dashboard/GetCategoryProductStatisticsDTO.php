<?php
namespace App\DTO\Dashboard;

class GetCategoryProductStatisticsDTO{
    public int $category_id;

    public function __construct(int $category_id)
    {
        $this->category_id=$category_id;
    }

    public static function FormRequest($request)
    {
        return new self($request->input("category_id"));
    }
}