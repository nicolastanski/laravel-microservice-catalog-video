<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreUpdateInputDTO;
use Core\UseCase\DTO\Genre\GenreUpdateOutputDTO;
use Core\UseCase\Interfaces\TransactionInterface;

class UpdateGenreUseCase
{
    protected $repository;
    protected $transaction;
    protected $categoryRepository;

    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(GenreUpdateInputDTO $input): GenreUpdateOutputDTO
    {
        $genre = $this->repository->findById($input->id);

        try {
            $genre->update(
                name: $input->name,
            );
            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $this->validateCategoriesId($input->categoriesId);

            $genreDB = $this->repository->update($genre);

            $this->transaction->commit();

            return new GenreUpdateOutputDTO(
                id: (string) $genreDB->id,
                name: $genreDB->name,
                is_active: $genreDB->isActive,
                created_at: $genreDB->createdAt()
            );


        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}
