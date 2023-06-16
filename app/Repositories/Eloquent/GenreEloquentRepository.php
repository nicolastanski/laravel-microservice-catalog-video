<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $genre)
    {
        $this->model = $genre;
    }

    public function insert(Genre $genre): Genre
    {
        $genreDb = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt(),
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreDb);
    }

    public function findById(string $genreId): Genre
    {
        if (!$genre = $this->model->find($genreId)) {
            throw new NotFoundException("Genre {$genreId} not found");
        }

        return $this->toGenre($genre);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model->where(function ($query) use ($filter) {
            if ($filter) {
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })
        ->orderBy('name', $order)
        ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('name', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(Genre $genre): Genre
    {
        if (!$genreDb = $this->model->find($genre->id())) {
            throw new NotFoundException("Genre {$genre->id} not found");
        }

        $genreDb->update([
            'name' => $genre->name,
            'is_active' => $genre->isActive,
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        $genreDb->refresh();

        return $this->toGenre($genreDb);
    }

    public function delete(string $genreId): bool
    {
        if (!$genreDb = $this->model->find($genreId)) {
            throw new NotFoundException("Genre {$genreId} not found");
        }

        return $genreDb->delete();
    }

    private function toGenre(Model $object): Genre
    {
        $entity = new Genre(
            id: new Uuid($object->id),
            name: $object->name,
            createdAt: new DateTime(($object->created_at)),
        );

        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
