<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as Model;
use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\TransactionDB;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\GenreCreateInputDTO;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function test_insert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new TransactionDB(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $useCase->execute(
            new GenreCreateInputDTO(
                name: 'teste',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'teste',
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExceptionInsertGenreWithCategoriesIdsInvalid()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new TransactionDB(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        $useCase->execute(
            new GenreCreateInputDto(
                name: 'teste',
                categoriesId: $categoriesIds
            )
        );
    }

    public function testTransactionInsert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new TransactionDB(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $useCase->execute(
                new GenreCreateInputDto(
                    name: 'teste',
                    categoriesId: $categoriesIds
                )
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'teste',
            ]);

            $this->assertDatabaseCount('category_genre', 10);
        } catch (\Throwable $th) {
            //throw $th;
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
