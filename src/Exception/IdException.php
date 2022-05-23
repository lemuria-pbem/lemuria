<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when an invalid ID is encountered.
 */
class IdException extends \InvalidArgumentException
{
	public function __construct(string $invalidId) {
		$message = '"' . $invalidId . '" is not a valid Lemuria ID.';
		parent::__construct($message);
	}
}
