<?php
declare (strict_types = 1);
namespace Lemuria;

use Psr\Log\LoggerInterface;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Hostilities;
use Lemuria\Engine\Orders;
use Lemuria\Engine\Report;
use Lemuria\Engine\Score;
use Lemuria\Exception\InitializationException;
use Lemuria\Exception\VersionTooLowException;
use Lemuria\Factory\Namer;
use Lemuria\Model\Builder;
use Lemuria\Model\Calendar;
use Lemuria\Model\Catalog;
use Lemuria\Model\Config;
use Lemuria\Model\Game;
use Lemuria\Model\World;
use Lemuria\Version\Module;
use Lemuria\Version\VersionFinder;

/**
 * Format a number.
 */
function number(int|float $number): string {
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
function isClass(string $type): bool {
	$n = strlen($type);
	return $n >= 3 && strpos($type, '\\') >= 1 && strrpos($type, '\\') <= $n - 2;
}

/**
 * Return the class namespace.
 */
function getNamespace(object|string $object): string {
	$class = is_object($object) ? $object::class : $object;
	$i     = strrpos($class, '\\');
	return $i > 0 ? substr($class, 0, $i) : $class;
}

/**
 * Return the class name of an object without its namespace.
 */
function getClass(object|string $object): string {
	$class = is_object($object) ? $object::class : $object;
	$i     = strrpos($class, '\\');
	return $i > 0 ? substr($class, $i + 1) : $class;
}

/**
 * Checks if a string has a given prefix.
 */
function hasPrefix(string $prefix, mixed $subject): bool {
	return $prefix === '' || str_starts_with((string)$subject, $prefix);
}

/**
 * Check if given string represents an integer.
 */
function isInt(string $subject): bool {
	return (string)(int)$subject === $subject;
}

/**
 * The mathematical signum function.
 *
 * @return int 1 if argument is greater or equal to zero, -1 otherwise.
 */
function sign(mixed $number): int {
	return $number >= 0 ? 1 : -1;
}

/**
 * Checks if a number is between two values, inclusively.
 */
function isBetween(mixed $minimum, mixed $value, mixed $maximum): bool {
	return $value >= $minimum && $value <= $maximum;
}

/**
 * Checks if a number is greater than zero, less than zero or equal to zero.
 *
 * @return int 1 if argument is greater than zero, -1 if argument is less than zero, 0 otherwise.
 */
function direction(mixed $number): int {
	return $number > 0 ? 1 : ($number < 0 ? -1 : 0);
}

/**
 * Implementation of multibyte str_pad.
 */
function mbStrPad(string $string, int $length, string $char = ' ', int $padType = STR_PAD_RIGHT): string {
	$additional = strlen($string) - mb_strlen($string);
	return str_pad($string, $length + $additional, $char, $padType);
}

/**
 * Implementation of multibyte ucfirst.
 */
function mbUcFirst(string $string): string {
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
 * Check if a string ends with one of a given set of characters.
 */
function endsWith(string $string, array $chars): bool {
	$last = substr($string, -1);
	return in_array($last, $chars);
}

/**
 * Get a random integer.
 */
function randInt(int $min = 0, int $max = PHP_INT_MAX): int {
	return Lemuria::Random()->getInt($min, $max);
}

/**
 * Get a random float between 0 and 1.
 */
function randFloat(): float {
	return randInt() / PHP_INT_MAX;
}

/**
 * Calculate a random chance.
 */
function randChance(float $chance): bool {
	return randFloat() <= $chance;
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

	private static ?Randomizer $random = null;

	private readonly FeatureFlag $featureFlag;

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

	private readonly Statistics $statistics;

	private readonly Namer $namer;

	private readonly Version $version;

	public static function FeatureFlag(): FeatureFlag {
		return self::getInstance()->featureFlag;
	}

	/**
	 * @throws InitializationException
	 */
	public static function Builder(): Builder {
		return self::getInstance()->builder;
	}

	/**
	 * @throws InitializationException
	 */
	public static function Calendar(): Calendar {
		return self::getInstance()->calendar;
	}

	/**
	 * @throws InitializationException
	 */
	public static function Catalog(): Catalog {
		return self::getInstance()->catalog;
	}

	public static function Debut(): Debut {
		return self::getInstance()->debut;
	}

	/**
	 * @throws InitializationException
	 */
	public static function Game(): Game {
		return self::getInstance()->game;
	}

	public static function Log(): LoggerInterface {
		return self::getInstance()->log;
	}

	public static function Orders(): Orders {
		return self::getInstance()->orders;
	}

	public static function Report(): Report {
		return self::getInstance()->report;
	}

	public static function Score(): Score {
		return self::getInstance()->score;
	}

	public static function Hostilities(): Hostilities {
		return self::getInstance()->hostilities;
	}

	/**
	 * @throws InitializationException
	 */
	public static function World(): World {
		return self::getInstance()->world;
	}

	public static function Registry(): Registry {
		return self::getInstance()->registry;
	}

	public static function Statistics(): Statistics {
		return self::getInstance()->statistics;
	}

	public static function Namer(): Namer {
		return self::getInstance()->namer;
	}

	public static function Version(): Version {
		return self::getInstance()->version;
	}

	public static function Random(): Randomizer {
		if (!self::$random) {
			self::$random = new Randomizer(new Xoshiro256StarStar());
		}
		return self::$random;
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
		self::validateVersion();
		self::Catalog()->load();
		self::Debut()->load();
		self::Orders()->load();
		self::Report()->load();
		self::Score()->load();
		self::Hostilities()->load();
		self::World()->load();
		self::Statistics()->load();
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
		self::Statistics()->save();
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
			$this->featureFlag = $config->FeatureFlag();
			$this->log         = $config->Log()->getLogger();
			$this->builder     = $config->Builder();
			$this->game        = $config->Game();
			$this->calendar    = $config->Calendar();
			$this->catalog     = $config->Catalog();
			$this->debut       = $config->Debut();
			$this->world       = $config->World();
			$this->hostilities = $config->Hostilities();
			$this->registry    = $config->Registry();
			$this->statistics  = $config->Statistics();
			$this->namer       = $config->Namer();
			$this->version     = new Version();
			$this->addVersions();
		} catch (\Exception $e) {
			die((string)$e);
		}
	}

	private function addVersions(): void {
		$versionFinder                     = new VersionFinder(__DIR__ . '/..');
		$this->version[Module::Base]       = $versionFinder->get();
		$this->version[Module::Model]      = $this->catalog->getVersion();
		$this->version[Module::Statistics] = $this->statistics->getVersion();
	}

	private static function validateVersion(): void {
		$version       = self::Version();
		$tag           = $version[Module::Model][0];
		$compatibility = self::Calendar()->getCompatibility();
		if ($compatibility > $tag->version) {
			throw new VersionTooLowException($compatibility);
		}
	}
}
