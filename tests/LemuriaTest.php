<?php
/** @noinspection SpellCheckingInspection */
declare(strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SATHub\PHPUnit\Base;

use function Lemuria\endsWith;
use function Lemuria\getClass;
use function Lemuria\hasPrefix;
use function Lemuria\isClass;
use function Lemuria\isInt;
use function Lemuria\mbUcFirst;
use function Lemuria\randChance;
use function Lemuria\randDistribution23;
use function Lemuria\sign;
use function Lemuria\undupChar;
use Lemuria\Exception\InitializationException;
use Lemuria\Lemuria;

use Lemuria\Tests\Mock\Model\CatalogMock;
use Lemuria\Tests\Mock\Model\ConfigMock;

class LemuriaTest extends Base
{
	#[Test]
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

	#[Test]
	public function getClass(): void {
		$this->assertSame('LemuriaTest', getClass($this));
	}

	#[Test]
	public function hasPrefix(): void {
		$this->assertTrue(hasPrefix('Psr', LoggerInterface::class));
	}

	#[Test]
	public function hasPrefixFalse(): void {
		$this->assertFalse(hasPrefix('Test', self::class));
	}

	#[Test]
	public function isInt(): void {
		$this->assertTrue(isInt('-1'));
		$this->assertTrue(isInt('0'));
		$this->assertTrue(isInt('1'));
		$this->assertTrue(isInt('1234567890'));
	}

	#[Test]
	public function isIntFalse(): void {
		$this->assertFalse(isInt(''));
		$this->assertFalse(isInt('null'));
		$this->assertFalse(isInt('1.23'));
		$this->assertFalse(isInt('-0.95'));
		$this->assertFalse(isInt('1e1'));
	}

	#[Test]
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
	 * @noinspection SpellCheckingInspection
	 */
	#[Test]
	public function mbUcFirst(): void {
		$this->assertSame('Kräuterkunde', mbUcFirst('kräuterkunde'));
		$this->assertSame('Älchemie', mbUcFirst('älchemie'));
	}

	#[Test]
	public function undupChar(): void {
		$this->assertSame('Ein Satz mit X.', undupChar(' ', 'Ein Satz  mit    X.'));
	}

	#[Test]
	public function endsWith(): void {
		$this->assertFalse(endsWith('Hilfe!', ['e', '.', '?']));
		$this->assertTrue(endsWith('Hilfe!', ['e', '.', '?', '!']));
	}

	#[Test]
	public function randChanceZero(): void {
		for ($i = 0; $i < 1000; $i++) {
			$this->assertFalse(randChance(0.0));
		}
	}

	#[Test]
	public function randChanceEqual(): void {
		$above = 0;
		$below = 0;
		for ($i = 0; $i < 1000; $i++) {
			randChance(0.5) ? $below++ : $above++;
		}
		$this->assertGreaterThan(0, $above);
		$this->assertGreaterThan(0, $below);
	}

	#[Test]
	public function randChanceOne(): void {
		for ($i = 0; $i < 1000; $i++) {
			$this->assertTrue(randChance(1.0));
		}
	}

	#[Test]
	public function randDistribution23(): void {
		$this->assertSame([0.0], randDistribution23(0));
		$this->assertSame([1.0], randDistribution23(-1));
		$this->assertSame([1.0], randDistribution23(1));
		$this->assertSame([0.6666667, 1.0], randDistribution23(-2));
		$this->assertSame([0.6666667, 1.0], randDistribution23(2));
		$this->assertSame([0.5, 0.8333333, 1.0], randDistribution23(-3));
		$this->assertSame([0.5, 0.8333333, 1.0], randDistribution23(3));
		$this->assertSame([0.4, 0.7, 0.9, 1.0], randDistribution23(-4));
		$this->assertSame([0.4, 0.7, 0.9, 1.0], randDistribution23(4));
		$this->assertSame([0.3333333, 0.6, 0.8, 0.9333333, 1.0], randDistribution23(-5));
		$this->assertSame([0.3333333, 0.6, 0.8, 0.9333333, 1.0], randDistribution23(5));
	}

	#[Test]
	public function testLog(): void {
		$this->expectException(InitializationException::class);
		Lemuria::Log();
	}

	#[Test]
	#[Depends('testLog')]
	public function testInit(): void {
		Lemuria::init(new ConfigMock());

		$this->assertInstanceOf(NullLogger::class, Lemuria::Log());
		$this->assertInstanceOf(CatalogMock::class, Lemuria::Catalog());
	}
}
