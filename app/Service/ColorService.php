<?php
namespace App\Service;

use App\DTO\Color\CreateColorDTO;
use App\DTO\Color\DeleteColorDTO;
use App\Repository\ColorRepository;
use App\ReturnTrait;

class ColorService{
    use ReturnTrait;
    protected $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository=$colorRepository;
    }

    public function createColor(CreateColorDTO $dto)
    {
        try{
            $color=$this->colorRepository->createColor($dto);
            return $this->success(true,"Color created successfully",$color,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function getColors()
    {
        try{
            $colors=$this->colorRepository->getColors();

            if($colors->isEmpty())
            {
                return $this->error(false,"Colors not found",[],404);
            }

            return $this->success(true,"Colors retrieved successfully",$colors,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteColor(DeleteColorDTO $dto)
    {
        try{
            $color=$this->colorRepository->findColorByID($dto->color_id);
            if(!$color)
            {
                return $this->error(false,"Color not found",null,404);
            }
            $this->colorRepository->deleteColor($color);

            return $this->success(true,"Color deleted successfully",null,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

}