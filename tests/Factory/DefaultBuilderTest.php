<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Factory;

use PHPUnit\Framework\Attributes\Test;

use Lemuria\Factory\DefaultBuilder;
use Lemuria\Model\Builder;

use Lemuria\Tests\Base;

class DefaultBuilderTest extends Base
{
	#[Test]
	public function construct(): void {
		$this->assertInstanceOf(Builder::class, new DefaultBuilder());
	}
}
