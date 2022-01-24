<?php
declare (strict_types = 1);
namespace Lemuria\Model\Exception;

use Lemuria\Model\Location;

/**
 * A MapException is throw when a location is not part of a world.
 */
final class MapException extends ModelException
{
	public function __construct(Location $location) {
		$message = "Location " . $location->Id()->Id() . " is not on this world's map.";
		parent::__construct($message);
	}
}
