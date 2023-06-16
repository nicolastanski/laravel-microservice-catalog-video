<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list()
    {
        $category = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(new CategoryInputDTO(
            id: $category->id
        ));

        $this->assertEquals($category->id, $responseUseCase->id);
        $this->assertEquals($category->name, $responseUseCase->name);
        $this->assertEquals($category->description, $responseUseCase->description);
    }
}
