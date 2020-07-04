<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\IdException;

/**
 * Implementation of the general Base36 ID, used for Parties, Units, Buildings and so on.
 */
final class Id
{
	private int $id;

	/**
	 * Convert a Base36 ID to its integer representation.
	 *
	 * @param string $id
	 * @return Id
	 * @throws IdException
	 */
	public static function fromId(string $id): Id {
		return new Id((int)base_convert(self::clean($id), 36, 10));
	}

	/**
	 * Create a Base36 ID from its integer representation.
	 *
	 * @param int $id
	 */
	public function __construct(int $id) {
		$this->id = $id;
	}

	/**
	 * Get the Base36 ID as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return base_convert($this->id, 10, 36);
	}

	/**
	 * Get the integer representation.
	 *
	 * @return int
	 */
	public function Id(): int {
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return string
	 * @throws IdException
	 */
	private static function clean(string $id): string {
		$cleanId = strtolower(trim($id));
		if (preg_match('/^[0-9a-z]+$/', $cleanId) !== 1) {
			throw new IdException($id);
		}
		return $cleanId;
	}
}
