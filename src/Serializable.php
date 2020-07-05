<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * The interface for serializable models.
 */
interface Serializable
{
	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array
	 */
	public function serialize(): array;

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array $data
	 * @return Serializable
	 */
	public function unserialize(array $data): Serializable;
}
