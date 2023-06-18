<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Tests\Mock\CountableMock;

class CountableTraitTest extends Base
{
	protected CountableMock $sut;

	#[Before]
	protected function initSUT(): void {
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
