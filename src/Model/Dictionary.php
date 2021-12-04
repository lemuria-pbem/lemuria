<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\Pure;

use function Lemuria\getClass;
use Lemuria\Lemuria;
use Lemuria\Singleton;

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

	#[Pure] public function has(string $keyPath, Singleton|string|int|null $index = null): bool {
		if ($index instanceof Singleton) {
			$index = getClass($index);
		}
		$default = $index === null ? $keyPath : $keyPath . '.' . $index;
		return $this->get($keyPath, $index) !== $default;
	}

	/**
	 * Get a string.
	 */
	#[Pure] public function get(string $keyPath, Singleton|string|int|null $index = null): string {
		if ($index instanceof Singleton) {
			$index = getClass($index);
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
}
