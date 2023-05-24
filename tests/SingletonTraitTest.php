<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Test;

use Lemuria\Singleton;

use Lemuria\Tests\Mock\SingletonMock;

class SingletonTraitTest extends Base
{
	#[Test]
	public function testToString(): void {
		$mock = new SingletonMock();

		$this->assertInstanceOf(Singleton::class, $mock);
		$this->assertSame('SingletonMock', (string)$mock);
	}
}
