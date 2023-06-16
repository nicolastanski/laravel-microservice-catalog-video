<?php

namespace Tests\Unit\UseCase;

use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\DTO\CastMember\CastMemberCreateInputDTO;
use Core\UseCase\DTO\CastMember\CastMemberCreateOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        // arrange
        $mockEntity = Mockery::mock(EntityCastMember::class, ['name', CastMemberType::ACTOR]);
        $mockEntity->shouldReceive('id');
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')
                            ->once()
                            ->andReturn($mockEntity);
        $useCase = new CreateCastMemberUseCase($mockRepository);

        $mockDto = Mockery::mock(CastMemberCreateInputDTO::class, [
            'name', 1,
        ]);

        // action
        $response = $useCase->execute($mockDto);

        // assert
        $this->assertInstanceOf(CastMemberCreateOutputDTO::class, $response);
        $this->assertNotEmpty($response->id);
        $this->assertEquals('name', $response->name);
        $this->assertEquals(1, $response->type);
        $this->assertNotEmpty($response->created_at);

        Mockery::close();
    }
}
