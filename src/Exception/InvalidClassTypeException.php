<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use function Lemuria\getClass;

class InvalidClassTypeException extends LemuriaException
{
	public function __construct(string $actual, string $expected, \Throwable $previous = null) {
		parent::__construct(getClass($actual) . ' is not an instance of ' . getClass($expected) . '.', $previous);
	}
}
