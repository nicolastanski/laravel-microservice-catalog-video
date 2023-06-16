<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullCustomMessageException()
    {
        try {
            $value = '';
            DomainValidation::notNull($value, 'custom message error');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function testStrMaxLength()
    {
        try {
            $value = 'teste';
            DomainValidation::strMaxLength($value, 3, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }

    public function testStrMinLength()
    {
        try {
            $value = 'teste';
            DomainValidation::strMinLength($value, 8, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }

    public function testStrCanNullAndMaxLength()
    {
        try {
            $value = 'teste';
            DomainValidation::strCanNullAndMaxLength($value, 3, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }
}