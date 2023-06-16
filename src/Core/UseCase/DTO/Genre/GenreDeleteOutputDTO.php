<?php

namespace Core\UseCase\DTO\Genre;

class GenreDeleteOutputDTO
{
    public function __construct(
        public bool $success
    ) {

    }

}
