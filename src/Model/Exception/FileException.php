<?php
declare (strict_types = 1);
namespace Lemuria\Model\Exception;

/**
 * This exception is thrown when data from a file is not available or in an unsupported format.
 */
class FileException extends \RuntimeException {

	/**
	 * Create exception.
	 *
	 * @param string $message
	 * @param \Throwable|null $previous
	 */
	public function __construct($message = '', \Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
	}
}
