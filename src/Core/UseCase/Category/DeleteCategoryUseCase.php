<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryDeleteOutputDTO;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDTO;

class DeleteCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;       
    }

    public function execute(CategoryInputDTO $input): CategoryDeleteOutputDTO
    {
        $responseDelete = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDTO(
            success: $responseDelete
        );

    }
}