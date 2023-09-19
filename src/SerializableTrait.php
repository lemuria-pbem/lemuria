<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Exception\UnserializeException;

/**
 * Helper methods for serialization.
 */
trait SerializableTrait
{
	/**
	 * Check that a serialized data array is valid.
	 *
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(array $data): void {
	}

	/**
	 * Check that a serialized data array has key, and return the value.
	 *
	 * @throws UnserializeEntityException
	 */
	protected function validateDataKey(array $data, int|string $key): mixed {
		if (array_key_exists($key, $data)) {
			return $data[$key];
		}
		throw new UnserializeException('Serialized data has no ' . $key . '.');
	}

	/**
	 * @throws UnserializeEntityException
	 */
	protected function validateIfExists(array $data, int|string $key, Validate $type): void {
		if (isset($data[$key])) {
			$this->validate($data, $key, $type);
		}
	}

	/**
	 * Validate that a serialized data array has a specific value.
	 *
	 * @throws UnserializeEntityException
	 */
	protected function validate(array $data, int|string $key, Validate $type): void {
		$validate = $type->value;
		if (str_starts_with($validate, '?')) {
			if (array_key_exists($key, $data) && $data[$key] === null) {
				return;
			}
			$validate = substr($validate, 1);
		}

		$isType = 'is_' . $validate;
		if (!isset($data[$key]) || !$isType($data[$key])) {
			throw new UnserializeEntityException($key, $type);
		}
	}

	protected function validateEnum(array $data, int|string $key, string $enum): void {
		if (class_exists($enum)) {
			$reflection = new \ReflectionClass($enum);
			if ($reflection->isEnum()) {
				if ($reflection->hasMethod('tryFrom')) {
					if (isset($data[$key]) && $enum::tryFrom($data[$key])) {
						return;
					}
				} else {
					if (isset($data[$key])) {
						$value = $data[$key];
						foreach ($enum::cases() as $case) {
							if ($value === $case->name) {
								return;
							}
						}
					}
				}
				throw new UnserializeException('Serialized data has no ' . $key . ' of type ' . getClass($enum) . '.');
			}
		}
		throw new LemuriaException('This method must be called with a enum class name.');
	}
}
