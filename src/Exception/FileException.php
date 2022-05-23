<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when data from a file is not available or in an unsupported format.
 */
class FileException extends \RuntimeException {

	public function __construct(string $message = '', \Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
	}
}
