<?php

namespace Core\UseCase\DTO\CastMember;

class CastMemberCreateInputDTO
{
    public function __construct(
        public string $name,
        public int $type
    ) {

    }

}
