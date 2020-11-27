<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Exception\IdException;
use Lemuria\Id;

class IdTest extends Test
{
	private const ID = 100;

	/**
	 * @test
	 */
	public function construct(): Id {
		$id = new Id(self::ID);

		$this->assertInstanceOf(Id::class, $id);

		return $id;
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Id(Id $id): void {
		$this->assertSame(self::ID, $id->Id());
	}

	/**
	 * @depends construct
	 */
	public function testToString(Id $id): void {
		$this->assertSame('2s', (string)$id);
	}

	/**
	 * @test
	 */
	public function fromId(): void {
		$id = Id::fromId(' A8 ');

		$this->assertInstanceOf(Id::class, $id);
		$this->assertSame(10 * 36 + 8, $id->Id());
	}

	/**
	 * @test
	 */
	public function fromIdFailsOnInvalidCharacters(): void {
		$this->expectException(IdException::class);
		Id::fromId('xö');
	}
}
