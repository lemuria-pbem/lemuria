<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Singleton;
use Lemuria\Tests\Mock\SingletonMock;

class SingletonTraitTest extends Test
{
	/**
	 * @test
	 */
	public function testToString(): void {
		$mock = new SingletonMock();

		$this->assertInstanceOf(Singleton::class, $mock);
		$this->assertSame('SingletonMock', (string)$mock);
	}
}
