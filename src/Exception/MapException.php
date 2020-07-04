<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use DomainException;

use Lemuria\Location;

/**
 * A MapException is throw when a region is not part of a world.
 */
final class MapException extends DomainException {

	/**
	 * @param Location $location
	 */
	public function __construct(Location $location) {
		$message = "Location " . $location->Id()->Id() . " is not on this world's map.";
		parent::__construct($message);
	}
}
