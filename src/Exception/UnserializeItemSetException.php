<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when a serialized data array does not only consist of items.
 */
class UnserializeItemSetException extends UnserializeException
{
	/**
	 * Create exception.
	 */
	public function __construct() {
		parent::__construct('Item set must only contain quantities of classes.');
	}
}
