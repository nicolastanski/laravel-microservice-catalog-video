<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\GenreOutputDTO;

class ListGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDTO $input): GenreOutputDTO
    {
        $response = $this->repository->findById(
            genreId: $input->id
        );

        return new GenreOutputDTO(
            id: (string) $response->id,
            name: $response->name,
            is_active: $response->isActive,
            created_at: $response->createdAt()
        );
    }
}
