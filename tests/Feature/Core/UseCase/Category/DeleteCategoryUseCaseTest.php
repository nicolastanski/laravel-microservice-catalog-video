<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $category = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new DeleteCategoryUseCase($repository);
        $useCase->execute(
            new CategoryInputDTO(
                id: $category->id
            )
        );

        $this->assertSoftDeleted($category);
    }
}
