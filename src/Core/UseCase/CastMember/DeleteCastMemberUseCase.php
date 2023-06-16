<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\CastMemberDeleteOutputDTO;
use Core\UseCase\DTO\CastMember\CastMemberInputDTO;

class DeleteCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDTO $input): CastMemberDeleteOutputDTO
    {
        $deleted = $this->repository->delete($input->id);

        return new CastMemberDeleteOutputDTO(
            success: $deleted
        );

    }
}
