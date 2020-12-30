<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Model\Coordinates;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
final class MapCoordinates implements Coordinates
{
	use SerializableTrait;

	#[Pure] public function __construct(private int $x = 0, private int $y = 0) {
	}

	#[Pure] public function __toString(): string {
		return '(' . $this->X() . ' ' . $this->Y() . ')';
	}

	/**
	 * Get a plain data array of the model's data.
	 */
	#[ArrayShape(['x' => 'int', 'y' => 'int'])]
	#[Pure]
	public function serialize(): array {
		return [
			'x' => $this->X(),
			'y' => $this->Y()
		];
	}

	/**
	 * Restore the model's data from serialized data.
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		$this->x = $data['x'];
		$this->y = $data['y'];
		return $this;
	}

	#[Pure] public function X(): int {
		return $this->x;
	}

	#[Pure] public function Y(): int {
		return $this->y;
	}

	/**
	 * @param array (string=>mixed) $data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(&$data): void {
		$this->validate($data, 'x', 'int');
		$this->validate($data, 'y', 'int');
	}
}
