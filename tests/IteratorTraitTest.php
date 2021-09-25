<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Tests\Mock\IteratorMock;

class IteratorTraitTest extends Test
{
	protected IteratorMock $sut;

	protected function setUp(): void {
		parent::setUp();
		$this->sut = new IteratorMock();
	}

	/**
	 * @test
	 */
	public function construct(): void {
		$i = 0;
		foreach ($this->sut as $item) {
			$this->assertIsString($item);
			$i++;
		}
		$this->assertSame(0, $i);
		$this->assertSame(0, $this->sut->count());
	}

	/**
	 * @test
	 */
	public function addItem(): void {
		$item = 'I am a test item';
		$this->sut->add($item);

		$i = 0;
		foreach ($this->sut as $item) {
			$this->assertIsString($item);
			$i++;
		}
		$this->assertSame(1, $i);
		$this->assertSame(1, $this->sut->count());
	}
}
