<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Engine\Move;

use Lemuria\Engine\Exception\EngineException;
use Lemuria\Engine\Move\CommandFile;

use Lemuria\Tests\Test;

class CommandFileTest extends Test
{
	private const PATH = __DIR__ . '/../../storage/orders/test.order';

	private const ORDER = [
		'PARTEI test "Nur1Test!"',
		'EINHEIT erste',
		'NUMMER 1',
		'// Kommentar über zwei Zeilen',
		'EINHEIT zwei',
		'MACHE Nichts',
		'NAME Einheit "Zu langer Name der darum in mehreren Zeilen steht"',
		'NÄCHSTER'
	];

	/**
	 * @test
	 */
	public function testFileExists(): void {
		$this->assertIsString(realpath(self::PATH));
	}

	/**
	 * @test
	 * @depends testFileExists
	 */
	public function construct(): CommandFile {
		$file = new CommandFile(self::PATH);

		$this->assertInstanceOf(\Iterator::class, $file);

		return $file;
	}

	/**
	 * @test
	 */
	public function constructThrowsException(): void {
		$this->expectException(EngineException::class);

		new CommandFile(__DIR__ . '/i-do-not-exist');
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function toStringReturnsPath(CommandFile $file): void {
		$path = realpath(self::PATH);

		$this->assertSame($path, (string)$file);
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function current(CommandFile $file): void {
		$this->assertSame('', $file->current());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function key(CommandFile $file): void {
		$this->assertSame(0, $file->key());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function valid(CommandFile $file): void {
		$this->assertFalse($file->valid());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function iteration(CommandFile $file): void {
		$i = 0;

		foreach ($file as $key => $value) {
			$this->assertArrayHasKey($i, self::ORDER);
			$this->assertSame($i, $key);
			$this->assertSame(self::ORDER[$i++], $value);
		}
		$this->assertSame(count(self::ORDER), $i);
	}
}
