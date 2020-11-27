<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Engine\Move;

use Lemuria\Engine\Exception\EngineException;
use Lemuria\Engine\Move\CommandFile;

use Lemuria\Tests\Test;

class CommandFileTest extends Test
{
	/**
	 * @test
	 */
	public function constructThrowsException(): void {
		$this->expectException(EngineException::class);

		new CommandFile(__DIR__ . '/i-do-not-exist');
	}
}
