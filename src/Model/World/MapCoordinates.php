<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Model\Coordinates;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
final class MapCoordinates implements Coordinates
{
	use SerializableTrait;

	private int $x;

	private int $y;

	/**
	 * Init coordinates.
	 *
	 * @param int $x
	 * @param int $y
	 */
	public function __construct(int $x = 0, int $y = 0) {
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * Get a string representation.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return '(' . $this->X() . ' ' . $this->Y() . ')';
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array
	 */
	public function serialize(): array {
		return [
			'x' => $this->X(),
			'y' => $this->Y()
		];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array $data
	 * @return Serializable
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		$this->x = $data['x'];
		$this->y = $data['y'];
		return $this;
	}

	/**
	 * Get the x coordinate.
	 *
	 * @return int
	 */
	public function X(): int {
		return $this->x;
	}

	/**
	 * Get the y coordinate.
	 *
	 * @return int
	 */
	public function Y(): int {
		return $this->y;
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array (string=>mixed) &$data
	 */
	protected function validateSerializedData(&$data): void {
		$this->validate($data, 'x', 'int');
		$this->validate($data, 'y', 'int');
	}
}
