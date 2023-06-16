<?php

namespace Core\UseCase\DTO\Category;

class CategoryDeleteOutputDTO
{
    public function __construct(
        public bool $success = true,
    ) {
        
    }

}