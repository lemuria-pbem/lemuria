<?php
declare (strict_types = 1);
namespace Lemuria\Model\Exception;

use JetBrains\PhpStorm\Pure;

use Lemuria\Id;

/**
 * This exception is thrown when an entity is not registered.
 */
class NotRegisteredException extends ModelException
{
	#[Pure] public function __construct(Id $id) {
		$message = 'Entity ' . $id . ' is not registered in this catalog.';
		parent::__construct($message);
	}
}
