<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use Psr\Log\LoggerInterface;

use function Lemuria\isClass;
use function Lemuria\getClass;
use function Lemuria\hasPrefix;
use function Lemuria\isInt;
use function Lemuria\mbUcFirst;
use function Lemuria\randChance;
use function Lemuria\sign;
use Lemuria\Exception\InitializationException;
use Lemuria\Lemuria;

class LemuriaTest extends Test
{
	/**
	 * @test
	 */
	public function isClass(): void {
		$this->assertFalse(isClass(''));
		$this->assertFalse(isClass('X'));
		$this->assertFalse(isClass('X\\'));
		$this->assertFalse(isClass('X\\Y\\'));
		$this->assertFalse(isClass('XYZ'));
		$this->assertFalse(isClass('\\X\\'));
		$this->assertFalse(isClass('\\X'));
		$this->assertFalse(isClass('\\X\\Y'));
		$this->assertFalse(isClass('\\A\\B\\C'));
		$this->assertTrue(isClass('A\\B'));
		$this->assertTrue(isClass('Aa\\Be'));
		$this->assertTrue(isClass('A\\B\\C'));
	}

	/**
	 * @test
	 */
	public function getClass(): void {
		$this->assertSame('LemuriaTest', getClass($this));
	}

	/**
	 * @test
	 */
	public function hasPrefix(): void {
		$this->assertTrue(hasPrefix('Psr', LoggerInterface::class));
	}

	/**
	 * @test
	 */
	public function hasPrefixFalse(): void {
		$this->assertFalse(hasPrefix('Test', self::class));
	}

	/**
	 * @test
	 */
	public function isInt(): void {
		$this->assertTrue(isInt('-1'));
		$this->assertTrue(isInt('0'));
		$this->assertTrue(isInt('1'));
		$this->assertTrue(isInt('1234567890'));
	}

	/**
	 * @test
	 */
	public function isIntFalse(): void {
		$this->assertFalse(isInt(''));
		$this->assertFalse(isInt('null'));
		$this->assertFalse(isInt('1.23'));
		$this->assertFalse(isInt('-0.95'));
		$this->assertFalse(isInt('1e1'));
	}

	/**
	 * @test
	 */
	public function sign(): void {
		$this->assertSame(-1, sign(-0.123));
		$this->assertSame(-1, sign(-0.123e3));
		$this->assertSame(-1, sign(-2));
		$this->assertSame(-1, sign(-1));
		$this->assertSame(-1, sign(''));
		$this->assertSame(-1, sign('-0.123e1'));
		$this->assertSame(-1, sign('-eins'));
		$this->assertSame(1, sign(0));
		$this->assertSame(1, sign(0.0));
		$this->assertSame(1, sign(1));
		$this->assertSame(1, sign(2));
		$this->assertSame(1, sign(0.123));
		$this->assertSame(1, sign(1.23e+2));
		$this->assertSame(1, sign(null));
		$this->assertSame(1, sign(false));
		$this->assertSame(1, sign('eins'));
		$this->assertSame(1, sign('0.123e-1'));
	}

	/**
	 * @test
	 */
	public function mbUcFirst(): void {
		$this->assertSame('Kräuterkunde', mbUcFirst('kräuterkunde'));
		$this->assertSame('Älchemie', mbUcFirst('älchemie'));
	}

	/**
	 * @test
	 */
	public function randChanceZero(): void {
		for ($i = 0; $i < 1000; $i++) {
			$this->assertFalse(randChance(0.0));
		}
	}

	/**
	 * @test
	 */
	public function randChanceEqual(): void {
		$above = 0;
		$below = 0;
		for ($i = 0; $i < 1000; $i++) {
			randChance(0.5) ? $below++ : $above++;
		}
		$this->assertGreaterThan(0, $above);
		$this->assertGreaterThan(0, $below);
	}

	/**
	 * @test
	 */
	public function randChanceOne(): void {
		for ($i = 0; $i < 1000; $i++) {
			$this->assertTrue(randChance(1.0));
		}
	}

	/**
	 * @test
	 */
	public function testLog(): void {
		$this->expectException(InitializationException::class);
		Lemuria::Log();
	}
}
