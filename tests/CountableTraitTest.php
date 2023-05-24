<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Test;

use Lemuria\Tests\Mock\CountableMock;

class CountableTraitTest extends Base
{
	protected CountableMock $sut;

	protected function setUp(): void {
		parent::setUp();
		$this->sut = new CountableMock();
	}

	#[Test]
	public function construct(): void {
		$this->assertSame(0, $this->sut->count());
	}

	#[Test]
	public function increase(): void {
		$this->sut->increase();

		$this->assertSame(1, $this->sut->count());
	}
}
