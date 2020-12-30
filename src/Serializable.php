<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * The interface for serializable models.
 */
interface Serializable
{
	public const TYPES = [
		'array', '?array', 'bool', '?bool', 'float', '?float', 'int', '?int', 'string', '?string'
	];

	/**
	 * Get a plain data array of the model's data.
	 */
	public function serialize(): array;

	/**
	 * Restore the model's data from serialized data.
	 */
	public function unserialize(array $data): Serializable;
}
