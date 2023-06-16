<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\DTO\CastMember\ListCastMembersInputDTO ;
use Core\UseCase\DTO\CastMember\ListCastMembersOutputDTO;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCastMembersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list()
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
                            ->once()
                            ->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);

        $mockInputDto = Mockery::mock(ListCastMembersInputDTO::class, [
            'filter', 'desc', 1, 15,
        ]);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMembersOutputDTO::class, $response);

        Mockery::close();
    }
}
