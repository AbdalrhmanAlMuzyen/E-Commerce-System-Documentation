<?php

namespace App\Repository;

use App\DTO\Color\CreateColorDTO;
use App\Models\Color;

class ColorRepository{
    public function createColor(CreateColorDTO $dto)
    {
        return Color::create([
            "name"=>$dto->name
        ]);
    }

    public function getColors()
    {
        return Color::all();
    }

    public function findColorByID($color_id)
    {
        return Color::find($color_id);
    }

    public function deleteColor($color)
    {
        return $color->delete();
    }
}