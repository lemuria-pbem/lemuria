<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

use Lemuria\Singleton;

class SingletonSetException extends \InvalidArgumentException
{
	#[Pure] public function __construct(Singleton|string $singleton) {
		$message = 'The set has no ' . $singleton . '.';
		parent::__construct($message);
	}
}
