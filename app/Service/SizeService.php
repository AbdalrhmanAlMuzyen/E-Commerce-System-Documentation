<?php
namespace App\Service;

use App\DTO\Size\CreateSizeDTO;
use App\DTO\Size\DeleteSizeDTO;
use App\Repository\SizeRepository;
use App\ReturnTrait;

class SizeService{
    use ReturnTrait;
    protected $sizeRepository;
    
    public function __construct(SizeRepository $sizeRepository)
    {
        $this->sizeRepository=$sizeRepository;
    }

    public function createSize(CreateSizeDTO $dto)
    {
        try{
            $size=$this->sizeRepository->createSize($dto);
            return $this->success(true,'Size created successfully',$size,201);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function getSizes()
    {
        try{
            $sizes=$this->sizeRepository->getSizes();

            if($sizes->isEmpty())
            {
                return $this->error(false,"Sizes not found",[],404);
            }

            return $this->success(true,"Sizes retrieved successfully",$sizes,200);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }
    }

    public function deleteSize(DeleteSizeDTO $dto)
    {
        try{
            $size=$this->sizeRepository->findSizeByID($dto->size_id);

            if(!$size)
            {
                return $this->error(false, 'Size not found', 404);
            } 
            
            $this->sizeRepository->deleteSize($size);
            return $this->success(true,'Size deleted successfully',null);
        }
        catch(\Exception $e)
        {
            return $this->error(false,$e->getMessage());
        }         
    }
}