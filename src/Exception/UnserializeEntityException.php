<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

/**
 * This exception is thrown when a serialized data array misses a specific value.
 */
class UnserializeEntityException extends UnserializeException
{
	#[Pure] public function __construct(string $key, string $type) {
		$message = 'Serialized data has no ' . $key . ' of type ' . $type . '.';
		parent::__construct($message);
	}
}
