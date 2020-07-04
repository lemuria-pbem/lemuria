<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use function Lemuria\getClass;
use Lemuria\Item;
use Lemuria\ItemSet;

/**
 * This exception is thrown by the ItemSet class.
 */
class ItemSetFillException extends \InvalidArgumentException {

	/**
	 * Create an exception for an item set that is filled with wrong item type.
	 *
	 * @param Item $item
	 * @param ItemSet $set
	 */
	public function __construct(Item $item, ItemSet $set) {
		$message = 'A ' . getClass($set) . ' set cannot be filled with ' . getClass($item) . ' items.';
		parent::__construct($message);
	}
}
