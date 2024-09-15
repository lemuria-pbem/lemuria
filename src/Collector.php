<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Dispatcher\AbstractEvent;

/**
 * A Collector is a class that manages at least one EntitySet.
 */
interface Collector extends Identifiable
{
	/**
	 * Get the event that triggers collection.
	 */
	public function getCollectAllEvent(): AbstractEvent;
}
