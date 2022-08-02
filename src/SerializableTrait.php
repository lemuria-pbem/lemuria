<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\UnserializeEntityException;

/**
 * Helper methods for serialization.
 */
trait SerializableTrait
{
	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array<string, mixed> &$data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(array &$data): void {
	}

	/**
	 * @throws UnserializeEntityException
	 */
	protected function validateIfExists(array &$data, string $key, string $type): void {
		if (isset($data[$key])) {
			$this->validate($data, $key, $type);
		}
	}

	/**
	 * Validate that a serialized data array has a specific value.
	 *
	 * @param array<string, mixed> $data
	 * @throws UnserializeEntityException
	 */
	protected function validate(array &$data, string $key, string $type): void
	{
		if (str_starts_with($type, '?')) {
			if (array_key_exists($key, $data) && $data[$key] === null) {
				return;
			}
			$type = substr($type, 1);
		}

		$isType = 'is_' . $type;
		if (!isset($data[$key]) || !$isType($data[$key])) {
			throw new UnserializeEntityException($key, $type);
		}
	}
}
