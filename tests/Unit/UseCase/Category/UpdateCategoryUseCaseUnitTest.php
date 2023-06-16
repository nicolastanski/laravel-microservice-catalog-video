<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function testRenameCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name Cat';
        $categoryDesc = 'Desc Cat';

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
            $categoryDesc
        ]);
        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        //$this->mockRepo->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepo->shouldReceive('findById')
            ->once() // once equivale a times(1)
            ->with($uuid)
            ->andReturn($this->mockEntity);

        $this->mockRepo->shouldReceive('update')->andReturn($this->mockEntity);

        $this->mockInputDTO = Mockery::mock(CategoryUpdateInputDTO::class, [
            $uuid,
            'new name'
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryUpdateOutputDTO::class, $responseUseCase);

          /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);

        $useCase = new UpdateCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDTO);
        $this->spy->showHaveReceived('findById');
        $this->spy->showHaveReceived('update');

        Mockery::close();
    }
}