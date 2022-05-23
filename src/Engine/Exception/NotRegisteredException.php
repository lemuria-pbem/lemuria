<?php
declare (strict_types = 1);
namespace Lemuria\Engine\Exception;

use Lemuria\Id;

/**
 * This exception is thrown when an entity is not registered.
 */
class NotRegisteredException extends EngineException
{
	public function __construct(Id $id) {
		$message = 'Entity ' . $id . ' is not registered in this report.';
		parent::__construct($message);
	}
}
