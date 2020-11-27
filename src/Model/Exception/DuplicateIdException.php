<?php
declare (strict_types = 1);
namespace Lemuria\Model\Exception;

use JetBrains\PhpStorm\Pure;

use Lemuria\Identifiable;

/**
 * This exception is thrown when an entity is registered twice.
 */
class DuplicateIdException extends ModelException
{
	#[Pure] public function __construct(Identifiable $identifiable) {
		$message = 'Entity ' . $identifiable->Id() . ' is already registered.';
		parent::__construct($message);
	}
}
