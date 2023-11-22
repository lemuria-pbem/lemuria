<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Model\Coordinates;
use Lemuria\SerializableTrait;
use Lemuria\Validate;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
final class MapCoordinates implements Coordinates
{
	use SerializableTrait;

	private const string X = 'x';

	private const string Y = 'y';

	public function __construct(private int $x = 0, private int $y = 0) {
	}

	public function __toString(): string {
		return '(' . $this->X() . ' ' . $this->Y() . ')';
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array<string, int>
	 */
	public function serialize(): array {
		return [
			self::X => $this->X(),
			self::Y => $this->Y()
		];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array<string, int> $data
	 */
	public function unserialize(array $data): static {
		$this->validateSerializedData($data);
		$this->x = $data[self::X];
		$this->y = $data[self::Y];
		return $this;
	}

	public function X(): int {
		return $this->x;
	}

	public function Y(): int {
		return $this->y;
	}

	/**
	 * @param array<string, int> $data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData($data): void {
		$this->validate($data, self::X, Validate::Int);
		$this->validate($data, self::Y, Validate::Int);
	}
}
