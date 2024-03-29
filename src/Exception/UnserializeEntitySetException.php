<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when a serialized data array does not only consist of IDs.
 */
class UnserializeEntitySetException extends UnserializeException
{
	public function __construct() {
		parent::__construct('Entity set must only contain integer IDs.');
	}
}
