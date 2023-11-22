<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\IdException;

/**
 * Implementation of the general Base36 ID, used for Parties, Units, Buildings and so on.
 */
final class Id implements \Stringable
{
	public final const string REGEX = '[0-9a-z]+';

	/**
	 * Convert a Base36 ID to its integer representation.
	 *
	 * @throws IdException
	 */
	public static function fromId(string $id): Id {
		return new Id((int)base_convert(self::clean($id), 36, 10));
	}

	/**
	 * Create a Base36 ID from its integer representation.
	 */
	public function __construct(private readonly int $id) {
	}

	/**
	 * Get the Base36 ID as a string.
	 */
	public function __toString(): string {
		return base_convert((string)$this->id, 10, 36);
	}

	/**
	 * Get the integer representation.
	 */
	public function Id(): int {
		return $this->id;
	}

	/**
	 * @throws IdException
	 */
	private static function clean(string $id): string {
		$cleanId = strtolower(trim($id));
		if (preg_match('/^' . self::REGEX . '$/', $cleanId) !== 1) {
			throw new IdException($id);
		}
		return $cleanId;
	}
}
