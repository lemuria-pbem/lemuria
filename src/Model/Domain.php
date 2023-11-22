<?php
declare(strict_types = 1);
namespace Lemuria\Model;

enum Domain : int
{
	private const int FACTOR = 100;

	case Party = 1;

	case Unit = 2;

	case Location = 3;

	case Construction = 4;

	case Vessel = 5;

	case Continent = 6;

	case Unicum = 7;

	case Trade = 8;

	case Realm = 9;

	public static function isLegacy(int $value): bool {
		return $value % self::FACTOR === 0 && $value >= self::FACTOR * self::Party->value && $value <= self::FACTOR * self::Realm->value;
	}

	public static function fromLegacy(int $value): self {
		if (self::isLegacy($value)) {
			return self::from((int)($value / 100));
		}
		return self::from($value);
	}

	public function getLegacyValue(): int {
		return self::FACTOR * $this->value;
	}
}
