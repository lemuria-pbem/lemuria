<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when a class is not a singleton.
 */
class SingletonException extends \DomainException {

	/**
	 * @param string $class
	 */
	public function __construct(string $class) {
		$message = $class . ' is not a singleton.';
		parent::__construct($message);
	}
}
