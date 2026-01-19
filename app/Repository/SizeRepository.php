<?php

namespace App\Repository;

use App\DTO\Size\CreateSizeDTO;
use App\Models\Size;

class SizeRepository{

    public function createSize(CreateSizeDTO $dto)
    {
        return Size::create([
            "value"=>$dto->value
        ]);
    }

    public function getSizes()
    {
        return Size::all();
    }
    
    public function findSizeByID($size_id)
    {
        return Size::find($size_id);
    }

    public function deleteSize($size)
    {
        return $size->delete();
    }
}