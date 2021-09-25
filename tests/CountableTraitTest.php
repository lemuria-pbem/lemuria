<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Tests\Mock\CountableMock;

class CountableTraitTest extends Test
{
	protected CountableMock $sut;

	protected function setUp(): void {
		parent::setUp();
		$this->sut = new CountableMock();
	}

	/**
	 * @test
	 */
	public function construct(): void {
		$this->assertSame(0, $this->sut->count());
	}

	/**
	 * @test
	 */
	public function increase(): void {
		$this->sut->increase();

		$this->assertSame(1, $this->sut->count());
	}
}
