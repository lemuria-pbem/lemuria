<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use Lemuria\Id;

/**
 * This exception is thrown by the EntitySet class.
 */
class EntitySetException extends \InvalidArgumentException
{
	/**
	 * @param Id $id
	 */
	public function __construct(Id $id) {
		$message = 'The entity ' . $id . ' is not part of the set.';
		parent::__construct($message);
	}
}
