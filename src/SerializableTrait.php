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
	 * @param array (string=>mixed) &$data
	 */
	protected function validateSerializedData(&$data): void {
	}

	/**
	 * Validate that a serialized data array has a specific value.
	 *
	 * @param array(string=>mixed) $data
	 * @param string $key
	 * @param string $type
	 */
	protected function validate(array &$data, string $key, string $type): void {
		if (strpos($type, '?') === 0) {
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
