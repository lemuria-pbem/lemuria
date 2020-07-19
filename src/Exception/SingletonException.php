<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

/**
 * This exception is thrown when a class is not a singleton.
 */
class SingletonException extends \DomainException
{
	/**
	 * @param string $class
	 * @param string $expected
	 */
	public function __construct(string $class, string $expected = 'singleton') {
		$message = $class . ' is not a ' . $expected . '.';
		parent::__construct($message);
	}
}
