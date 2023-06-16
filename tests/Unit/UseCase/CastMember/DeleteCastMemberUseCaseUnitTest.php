<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\DTO\CastMember\CastMemberDeleteOutputDTO;
use Core\UseCase\DTO\CastMember\CastMemberInputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class DeleteCastMermberUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
                            ->once()
                            ->andReturn(true);

        $mockInputDto = Mockery::mock(CastMemberInputDTO::class, [$uuid]);

        $useCase = new DeleteCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberDeleteOutputDTO::class, $response);

        Mockery::close();
    }
}
