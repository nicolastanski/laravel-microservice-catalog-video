<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CastMemberUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $castMember = new CastMember(
            id: new Uuid($uuid),
            name: 'New Cast',
            type: CastMemberType::ACTOR,
            createdAt: new DateTime($date)
        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('New Cast', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testAttributesNewEntity()
    {
        $castMember = new CastMember(
            name: 'New Cast',
            type: CastMemberType::DIRECTOR,
        );

        $this->assertNotEmpty($castMember->id());
        $this->assertEquals('New Cast', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function testValidation()
    {
        $this->expectException(EntityValidationException::class);

        new CastMember(
            name: 'ab',
            type: CastMemberType::DIRECTOR
        );
    }

    public function testExceptionUpdate()
    {
        $this->expectException(EntityValidationException::class);

        $catMember = new CastMember(
            name: 'ab',
            type: CastMemberType::DIRECTOR
        );

        $catMember->update(
            name: 'Updated'
        );

        $this->assertEquals('Updated', $catMember->name);
    }

    public function testUpdate()
    {
        $catMember = new CastMember(
            name: 'New Cast',
            type: CastMemberType::DIRECTOR
        );

        $this->assertEquals('New Cast', $catMember->name);

        $catMember->update(
            name: 'Updated'
        );

        $this->assertEquals('Updated', $catMember->name);
    }
}
