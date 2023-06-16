<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryDeleteOutputDTO;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(true);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

          /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDTO);
        $this->spy->showHaveReceived('delete');
    }

    public function testDeleteFalse()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(false);

        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDTO);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);

          /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(false);

        $useCase = new DeleteCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDTO);
        $this->spy->showHaveReceived('delete');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}