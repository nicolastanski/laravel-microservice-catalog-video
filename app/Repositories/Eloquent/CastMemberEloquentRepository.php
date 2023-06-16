<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(CastMember $castMember): CastMember
    {
        $dataDB = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt()
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function findById(string $castMemberId): CastMember
    {
        if (!$dataDB = $this->model->find($castMemberId)) {
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");
        }

        return $this->convertToEntity($dataDB);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $dataDB = $this->model->where(function ($query) use ($filter) {
            if ($filter) {
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })
        ->orderBy('name', $order)
        ->get();

        return $dataDB->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('name', $order);
        $paginator = $query->paginate($totalPage);

        return new PaginationPresenter($paginator);
    }

    public function update(CastMember $castMember): CastMember
    {
        if (!$dataDB = $this->model->find($castMember->id())) {
            throw new NotFoundException("Cast Member {$castMember->id()} Not Found");
        }

        $dataDB->update([
            'name' => $castMember->name,
            'type' => $castMember->type->value,
        ]);
        $dataDB->refresh();

        return $this->convertToEntity($dataDB);
    }

    public function delete(string $castMemberId): bool
    {
        if (!$dataDB = $this->model->find($castMemberId)) {
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");
        }

        return $dataDB->delete();
    }

    private function convertToEntity(Model $model): CastMember
    {
        return new CastMember(
            id: new ValueObjectUuid($model->id),
            name: $model->name,
            type: CastMemberType::from($model->type),
            createdAt: $model->created_at
        );
    }
}
