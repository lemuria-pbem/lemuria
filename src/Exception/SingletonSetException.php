<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use Lemuria\Singleton;

class SingletonSetException extends \InvalidArgumentException
{
	public function __construct(Singleton|string $singleton) {
		$message = 'The set has no ' . $singleton . '.';
		parent::__construct($message);
	}
}
