<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{

    public function test_updaste()
    {
        $category = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDTO(
                id: $category->id,
                name: 'name updated'
            )
        );

        $this->assertEquals('name updated', $responseUseCase->name);
        $this->assertEquals($category->description, $responseUseCase->description);

        $this->assertDatabaseHas('categories', [
            'name' => $responseUseCase->name
        ]);
    }
}
