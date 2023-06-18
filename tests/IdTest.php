<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Exception\IdException;
use Lemuria\Id;

class IdTest extends Base
{
	private const ID = 100;

	#[Test]
	public function construct(): Id {
		$id = new Id(self::ID);

		$this->pass();

		return $id;
	}

	#[Test]
	#[Depends('construct')]
	public function Id(Id $id): void {
		$this->assertSame(self::ID, $id->Id());
	}

	#[Depends('construct')]
	public function toStringCreatesBase36String(Id $id): void {
		$this->assertSame('2s', (string)$id);
	}

	#[Test]
	public function fromId(): void {
		$id = Id::fromId(' A8 ');

		$this->assertInstanceOf(Id::class, $id);
		$this->assertSame(10 * 36 + 8, $id->Id());
	}

	#[Test]
	public function fromIdFailsOnInvalidCharacters(): void {
		$this->expectException(IdException::class);

		Id::fromId('xö');
	}
}
