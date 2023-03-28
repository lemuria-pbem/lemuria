<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use Lemuria\Log;

class LogMock implements Log
{
	public function getLogger(): LoggerInterface {
		return new NullLogger();
	}
}
