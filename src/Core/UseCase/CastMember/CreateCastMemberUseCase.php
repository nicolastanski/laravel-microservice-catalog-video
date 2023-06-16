<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\CastMemberCreateInputDTO;
use Core\UseCase\DTO\CastMember\CastMemberCreateOutputDTO;

class CreateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberCreateInputDTO $input): CastMemberCreateOutputDTO
    {
        $entity = new CastMember(
            name: $input->name,
            type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR,
        );

        $this->repository->insert($entity);

        return new CastMemberCreateOutputDTO(
            id: $entity->id(),
            name: $entity->name,
            type: $entity->type->value,
            created_at: $entity->createdAt(),
        );
    }
}
