<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            'test category',
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
                    ->with($id)
                    ->andReturn($this->mockEntity);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [
            $id
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryOutputDTO::class, $responseUseCase);
        $this->assertEquals('test category', $responseUseCase->name);
        $this->assertEquals($id, $responseUseCase->id);
       
         /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);

        $useCase = new ListCategoryUseCase($this->spy);
        $responseUseCase = $useCase->execute($this->mockInputDTO);
        $this->spy->showHaveReceived('findById');

    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}