<?php

declare(strict_types=1);

namespace Unit\Generator;

use PHPUnit\Framework\TestCase;
use Stwarog\FuelFixturesGenerator\Config;
use Stwarog\FuelFixturesGenerator\FuelAwareNameGenerator;

/** @covers \Stwarog\FuelFixturesGenerator\FuelAwareNameGenerator */
final class FuelAwareNameGeneratorTest extends TestCase
{
    public function testGetFullNameClassNameIsInFuelSyntaxShouldAttachNameSpace(): void
    {
        // Given some Model in Fuel naming convention
        $class = 'Model_Orm_Currency';

        // And Name generator that holds configured namespace
        $namespace = 'Tests\Fixtures';
        $config = $this->createMock(Config::class);
        $config->method('getNamespace')->willReturn($namespace);

        $sut = new FuelAwareNameGenerator($config);

        // When getFullName requested
        $actual = $sut->getFullName($class);

        // Then result should be:
        $expected = '\Tests\Fixtures\ModelOrmCurrencyFixture';
        $this->assertSame($expected, $actual);
    }

    public function testGetShortNameClassNameIsInFuelSyntaxShouldNotAttachNameSpace(): void
    {
        // Given some Model in Fuel naming convention
        $class = 'Model_Orm_Currency';

        // And Name generator that holds configured namespace
        $namespace = 'Tests\Fixtures';
        $config = $this->createMock(Config::class);
        $config->method('getNamespace')->willReturn($namespace);

        $sut = new FuelAwareNameGenerator($config);

        // When shortName requested
        $actual = $sut->getShortName($class);

        // Then the result should be:
        $expected = '\ModelOrmCurrencyFixture';
        $this->assertSame($expected, $actual);
    }

    public function testGetFullNameClassNameIsInPsr4SyntaxShouldAddFixtureSuffix(): void
    {
        // Given some Model in PSR-4 naming convention
        $class = self::class;

        // And Name generator that holds configured namespace
        $namespace = 'Tests\Fixtures';
        $config = $this->createMock(Config::class);
        $config->method('getNamespace')->willReturn($namespace);

        $sut = new FuelAwareNameGenerator($config);

        // When getFullName requested
        $actual = $sut->getFullName($class);

        // Then the result should be:
        $expected = '\Unit\Generator\FuelAwareNameGeneratorTestFixture';
        $this->assertSame($expected, $actual);
    }

    public function testShortNameClassNameIsInPsr4SyntaxShouldAddFixtureSuffix(): void
    {
        // Given some Model in PSR-4 naming convention
        $class = self::class;

        // And Name generator that holds configured namespace
        $namespace = 'Tests\Fixtures';
        $config = $this->createMock(Config::class);
        $config->method('getNamespace')->willReturn($namespace);

        $sut = new FuelAwareNameGenerator($config);

        // When getFullName requested
        $actual = $sut->getShortName($class);

        // Then the result should be:
        $expected = '\FuelAwareNameGeneratorTestFixture';
        $this->assertSame($expected, $actual);
    }
}
