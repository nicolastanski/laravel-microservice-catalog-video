<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\CategoryCreateOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name Cat';

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        //$this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockRepo->shouldReceive('insert')
            ->times(1)
            ->andReturn($this->mockEntity);

        $this->mockInputDTO = Mockery::mock(CategoryCreateInputDTO::class, [
            $categoryName
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryCreateOutputDTO::class, $responseUseCase);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);

        $useCase = new CreateCategoryUseCase($this->spy);
        $responseUseCase = $useCase->execute($this->mockInputDTO);
        $this->spy->showHaveReceived('insert');

        Mockery::close();
    }
}