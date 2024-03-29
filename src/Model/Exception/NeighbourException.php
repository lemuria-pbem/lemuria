<?php
declare(strict_types = 1);
namespace Lemuria\Model\Exception;

use Lemuria\Model\Location;

/**
 * A NeighbourException is throw when a location is not a neighbour of another location.
 */
final class NeighbourException extends ModelException
{
	public function __construct(Location $location) {
		$message = "Location " . $location->Id()->Id() . " is not a neighbour.";
		parent::__construct($message);
	}
}
