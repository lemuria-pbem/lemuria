<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Tests\Mock\IteratorMock;

class IteratorTraitTest extends Base
{
	protected IteratorMock $sut;

	#[Before]
	protected function initSUT(): void {
		$this->sut = new IteratorMock();
	}

	#[Test]
	public function construct(): void {
		$i = 0;
		foreach ($this->sut as $item) {
			$this->assertIsString($item);
			$i++;
		}
		$this->assertSame(0, $i);
		$this->assertSame(0, $this->sut->count());
	}

	#[Test]
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
