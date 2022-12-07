<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use Lemuria\Validate;

/**
 * This exception is thrown when a serialized data array misses a specific value.
 */
class UnserializeEntityException extends UnserializeException
{
	public function __construct(int|string $key, Validate $type) {
		if (is_int($key)) {
			$key = 'index ' . $key;
		}
		$message = 'Serialized data has no ' . $key . ' of type ' . $type->name . '.';
		parent::__construct($message);
	}
}
