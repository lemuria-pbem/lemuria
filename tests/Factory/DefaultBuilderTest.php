<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Factory;

use Lemuria\Factory\DefaultBuilder;
use Lemuria\Model\Builder;

use Lemuria\Tests\Test;

class DefaultBuilderTest extends Test
{
	/**
	 * @test
	 */
	public function construct(): void {
		$this->assertInstanceOf(Builder::class, new DefaultBuilder());
	}
}
