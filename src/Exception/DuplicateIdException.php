<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use Lemuria\Identifiable;

/**
 * This exception is thrown when an entity is registered twice.
 */
class DuplicateIdException extends \DomainException
{
	/**
	 * @param Identifiable $identifiable
	 */
	public function __construct(Identifiable $identifiable) {
		$message = 'Entity ' . $identifiable->Id() . ' is already registered.';
		parent::__construct($message);
	}
}
