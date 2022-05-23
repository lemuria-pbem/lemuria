<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use Lemuria\Id;

/**
 * This exception is thrown by the EntitySet class.
 */
class EntitySetReplaceException extends \InvalidArgumentException
{
	public function __construct(Id $id) {
		$message = 'The entity ' . $id . ' must not be part of the set.';
		parent::__construct($message);
	}
}
