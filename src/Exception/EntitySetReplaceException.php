<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

use Lemuria\Id;

/**
 * This exception is thrown by the EntitySet class.
 */
class EntitySetReplaceException extends \InvalidArgumentException
{
	#[Pure] public function __construct(Id $id) {
		$message = 'The entity ' . $id . ' must not be part of the set.';
		parent::__construct($message);
	}
}
