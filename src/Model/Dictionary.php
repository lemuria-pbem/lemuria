<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Model\Exception\KeyPathException;
use function Lemuria\getClass;
use Lemuria\Lemuria;
use Lemuria\Singleton;
use function Lemuria\randElement;

final class Dictionary
{
	private static ?array $strings = null;

	/**
	 * Initialize the static strings.
	 */
	public function __construct() {
		if (!self::$strings) {
			self::$strings = Lemuria::Game()->getStrings();
		}
	}

	public function has(string $keyPath, Singleton|\BackedEnum|string|int|null $index = null): bool {
		if ($index instanceof Singleton) {
			$index = getClass($index);
		} elseif ($index instanceof \BackedEnum) {
			$index = $index->value;
		}
		$default = $index === null ? $keyPath : $keyPath . '.' . $index;
		return $this->get($keyPath, $index) !== $default;
	}

	/**
	 * Get a string.
	 */
	public function get(string $keyPath, Singleton|\BackedEnum|string|int|null $index = null): string {
		if ($index instanceof Singleton) {
			$index = getClass($index);
		} elseif ($index instanceof \BackedEnum) {
			$index = $index->value;
		}
		$strings =& self::$strings;
		$default = $index === null ? $keyPath : $keyPath . '.' . $index;

		foreach (explode('.', $keyPath) as $key) {
			if (is_array($strings) && array_key_exists($key, $strings)) {
				$strings =& $strings[$key];
			} else {
				return $default;
			}
		}
		if ($index === null) {
			if (is_array($strings)) {
				if (array_key_exists(0, $strings)) {
					return (string)$strings[0];
				}
				return $default;
			}
			return (string)$strings;
		} else {
			if (is_array($strings)) {
				if (array_key_exists($index, $strings)) {
					$strings =& $strings[$index];
					if (is_array($strings)) {
						if (array_key_exists(0, $strings)) {
							return (string)$strings[0];
						} else {
							return $default;
						}
					}
					return (string)$strings;
				}
				if (is_int($index) && $index > 1 && count($strings) === 2 &&
					array_key_exists(0, $strings) && array_key_exists(1, $strings)) {
					return (string)$strings[1];
				}
			}
			return $default;
		}
	}

	public function random(string $keyPath, Singleton|\BackedEnum|string|int|null $index = null): string {
		if ($index instanceof Singleton) {
			$index = getClass($index);
		} elseif ($index instanceof \BackedEnum) {
			$index = $index->value;
		}
		$strings = $this->raw($index === null ? $keyPath : $keyPath . '.' . $index);
		if (is_string($strings)) {
			$strings = [$strings];
		} elseif (!is_array($strings)) {
			throw new KeyPathException($keyPath);
		}

		$random = randElement($strings);
		if (is_string($random)) {
			return $random;
		}
		throw new KeyPathException($keyPath);
	}

	public function raw(string $keyPath): mixed {
		$strings =& self::$strings;
		foreach (explode('.', $keyPath) as $key) {
			if (!is_array($strings) || !array_key_exists($key, $strings)) {
				throw new KeyPathException($keyPath);
			}
			$strings =& $strings[$key];
		}
		return $strings;
	}
}
