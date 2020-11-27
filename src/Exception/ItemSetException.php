<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

use function Lemuria\getClass;
use Lemuria\Item;

/**
 * This exception is thrown by the ItemSet class.
 */
class ItemSetException extends \InvalidArgumentException
{
	/**
	 * Create an exception for an item that is not contained in an item set.
	 */
	#[Pure] public function __construct(Item $item) {
		$message = 'The set has no ' . getClass($item->getObject()) . ' that could be removed.';
		parent::__construct($message);
	}
}
