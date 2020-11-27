<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

/**
 * This exception is thrown when the program runs into a potential implementation bug.
 */
class LemuriaException extends \LogicException
{
	#[Pure] public function __construct(string $message = '', \Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
	}
}
