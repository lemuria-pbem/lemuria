<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Hostilities;
use Lemuria\Engine\Orders;
use Lemuria\Engine\Report;
use Lemuria\Engine\Score;
use Lemuria\Exception\InitializationException;
use Lemuria\Model\Builder;
use Lemuria\Model\Calendar;
use Lemuria\Model\Catalog;
use Lemuria\Model\Config;
use Lemuria\Model\Game;
use Lemuria\Model\World;
use Lemuria\Version\VersionFinder;

/**
 * Format a number.
 */
#[Pure] function number(int|float $number): string {
	$formattedNumber = $number < 0 ? '-' : '';
	$integer         = (int)abs($number);
	$string          = (string)$integer;
	$n               = strlen($string);
	$c               = $n;
	for ($i = 0; $i < $n; $i++) {
		if ($c-- % 3 === 0 && $i > 0) {
			$formattedNumber .= '.';
		}
		$formattedNumber .= $string[$i];
	}
	if (is_float($number)) {
		$string   = (string)$number;
		$i        = strpos($string, '.');
		$n        = strlen($string);
		$decimals = '0';
		if ($i !== false && ++$i < $n) {
			$decimals = substr($string, $i);
		}
		$formattedNumber .= ',' . $decimals;
	}
	return $formattedNumber;
}

/**
 * Check if a type is a namespaced class name.
 */
#[Pure] function isClass(string $type): bool {
	$n = strlen($type);
	return $n >= 3 && strpos($type, '\\') >= 1 && strrpos($type, '\\') <= $n - 2;
}

/**
 * Return the class namespace.
 *
 * @noinspection PhpPureFunctionMayProduceSideEffectsInspection
 */
#[Pure] function getNamespace(object|string $object): string {
	$class = is_object($object) ? $object::class : $object;
	$i     = strrpos($class, '\\');
	return $i > 0 ? substr($class, 0, $i) : $class;
}

/**
 * Return the class name of an object without its namespace.
 *
 * @noinspection PhpPureFunctionMayProduceSideEffectsInspection
 */
#[Pure] function getClass(object|string $object): string {
	$class = is_object($object) ? $object::class : $object;
	$i     = strrpos($class, '\\');
	return $i > 0 ? substr($class, $i + 1) : $class;
}

/**
 * Checks if a string has a given prefix.
 */
#[Pure] function hasPrefix(string $prefix, mixed $subject): bool {
	return $prefix === '' || str_starts_with((string)$subject, $prefix);
}

/**
 * Check if given string represents an integer.
 */
#[Pure] function isInt(string $subject): bool {
	return (string)(int)$subject === $subject;
}

/**
 * The mathematical signum function.
 *
 * @return int 1 if argument is greater or equal to zero, -1 otherwise.
 */
#[Pure] function sign(mixed $number): int {
	return $number >= 0 ? 1 : -1;
}

/**
 * Implementation of multibyte str_pad.
 */
#[Pure] function mbStrPad(string $string, int $length, string $char = ' ', int $padType = STR_PAD_RIGHT): string {
	$additional = strlen($string) - mb_strlen($string);
	return str_pad($string, $length + $additional, $char, $padType);
}

/**
 * Implementation of multibyte ucfirst.
 */
#[Pure] function mbUcFirst(string $string): string {
	$f = mb_strtoupper(mb_substr($string, 0, 1));
	return $f . mb_substr($string, 1);
}

/**
 * Remove duplicate character in a string.
 */
function undupChar(string $char, string $string): string {
	$search = $char . $char;
	$string = str_replace($search, $char, $string, $count);
	if ($count > 0) {
		return undupChar($char, $string);
	}
	return $string;
}

/**
 * Get a random number in the interval [0.0, 1.0].
 */
function random(): float {
	$max = 1000000;
	return rand(1, $max) / $max;
}

/**
 * Calculate a random chance.
 */
function randChance(float $chance): bool {
	return random() <= $chance;
}

/**
 * Get the "two-thirds random distribution" for a given amount.
 *
 * The result is an array containing $amount floating-point numbers that
 * represent a descending random chance, e.g. for amount of 3 it will return
 * [0.5, 0.833, 1.0] and for 4 it will return [0.4, 0.7, 0.9, 1.0].
 *
 * @return float[]
 */
function randDistribution23(int $amount): array {
	$amount = abs($amount);
	if ($amount === 0) {
		return [0.0];
	}

	$distribution = [];
	$chance       = 0.0;
	$divisor      = $amount + 1;
	for ($n = $divisor; $n >= 2; $n--) {
		$next           = $chance;
		$remaining      = 1.0 - $next;
		$chance         = $next + 2 / $n * $remaining;
		$distribution[] = round($chance, 7);
	}

	return $distribution;
}

/**
 * This singleton implementation offers factory methods.
 */
final class Lemuria
{
	private static ?self $instance = null;

	private readonly Builder $builder;

	private readonly Calendar $calendar;

	private readonly Catalog $catalog;

	private readonly Debut $debut;

	private readonly Game $game;

	private readonly LoggerInterface $log;

	private readonly Orders $orders;

	private readonly Report $report;

	private readonly World $world;

	private readonly Score $score;

	private readonly Hostilities $hostilities;

	private readonly Registry $registry;

	private readonly Version $version;

	/**
	 * Get the builder.
	 *
	 * @throws InitializationException
	 */
	public static function Builder(): Builder {
		return self::getInstance()->builder;
	}

	/**
	 * Get the Calendar.
	 *
	 * @throws InitializationException
	 */
	public static function Calendar(): Calendar {
		return self::getInstance()->calendar;
	}

	/**
	 * Get the Catalog.
	 *
	 * @throws InitializationException
	 */
	public static function Catalog(): Catalog {
		return self::getInstance()->catalog;
	}

	/**
	 * Get the Debut.
	 */
	public static function Debut(): Debut {
		return self::getInstance()->debut;
	}

	/**
	 * Get the Game.
	 *
	 * @throws InitializationException
	 */
	public static function Game(): Game {
		return self::getInstance()->game;
	}

	/**
	 * Get the log.
	 */
	public static function Log(): LoggerInterface {
		return self::getInstance()->log;
	}

	/**
	 * Get the orders.
	 */
	public static function Orders(): Orders {
		return self::getInstance()->orders;
	}

	/**
	 * Get the report.
	 */
	public static function Report(): Report {
		return self::getInstance()->report;
	}

	/**
	 * Get the score.
	 */
	public static function Score(): Score {
		return self::getInstance()->score;
	}

	/**
	 * Get the hostilities.
	 */
	public static function Hostilities(): Hostilities {
		return self::getInstance()->hostilities;
	}

	/**
	 * Get the World.
	 *
	 * @throws InitializationException
	 */
	public static function World(): World {
		return self::getInstance()->world;
	}

	/**
	 * Get the registry.
	 */
	public static function Registry(): Registry {
		return self::getInstance()->registry;
	}

	/**
	 * @return Version
	 */
	public static function Version(): Version {
		return self::getInstance()->version;
	}

	public static function init(Config $config): void {
		self::$instance         = new self($config);
		self::$instance->orders = $config->Orders();
		self::$instance->report = $config->Report();
		self::$instance->score  = $config->Score();
	}

	/**
	 * Init Lemuria for the current Game.
	 */
	public static function load(): void {
		self::Calendar()->load();
		self::Catalog()->load();
		self::Debut()->load();
		self::Orders()->load();
		self::Report()->load();
		self::Score()->load();
		self::Hostilities()->load();
		self::World()->load();
	}

	/**
	 * Save the current Game.
	 */
	public static function save(): void {
		self::Calendar()->save();
		self::Catalog()->save();
		self::Debut()->save();
		self::Orders()->save();
		self::Report()->save();
		self::Score()->save();
		self::Hostilities()->save();
		self::World()->save();
	}

	/**
	 * Dummy function for class loading when functions are used.
	 */
	public static function useFunctions(): void {
	}

	private static function getInstance(): Lemuria {
		if (!self::$instance) {
			throw new InitializationException();
		}
		return self::$instance;
	}

	private function __construct(Config $config) {
		try {
			$this->log         = $config->Log()->getLogger();
			$this->builder     = $config->Builder();
			$this->game        = $config->Game();
			$this->calendar    = $config->Calendar();
			$this->catalog     = $config->Catalog();
			$this->debut       = $config->Debut();
			$this->world       = $config->World();
			$this->hostilities = $config->Hostilities();
			$this->registry    = $config->Registry();
			$this->version     = new Version();
			$this->addVersions();
		} catch (\Exception $e) {
			die((string)$e);
		}
	}

	private function addVersions(): void {
		$versionFinder                 = new VersionFinder(__DIR__ . '/..');
		$this->version[Version::BASE]  = $versionFinder->get();
		$this->version[Version::MODEL] = $this->catalog->getVersion();
	}
}
