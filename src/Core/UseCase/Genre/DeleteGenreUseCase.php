<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreDeleteOutputDTO;
use Core\UseCase\DTO\Genre\GenreInputDTO;

class DeleteGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDTO $input): GenreDeleteOutputDTO
    {
        $success = $this->repository->delete($input->id);

        return new GenreDeleteOutputDTO(
            success: $success
        );
    }
}
