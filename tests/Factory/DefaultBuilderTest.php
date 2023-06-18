<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Factory;

use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Factory\DefaultBuilder;
use Lemuria\Model\Builder;

class DefaultBuilderTest extends Base
{
	#[Test]
	public function construct(): void {
		$this->assertInstanceOf(Builder::class, new DefaultBuilder());
	}
}
