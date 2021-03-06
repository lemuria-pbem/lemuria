<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;
use Lemuria\Version\VersionFinder;
use Psr\Log\LoggerInterface;

use Lemuria\Engine\Debut;
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
 * Return the class name of an object without it's namespace.
 */
#[Pure] function getClass(object|string $object): string {
	$class = is_object($object) ? $object::class : $object;
	$i     = strripos($class, '\\');
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
 * Implementation of multi-byte ucfirst.
 */
#[Pure] function mbUcFirst(string $string): string {
	$f = mb_strtoupper(mb_substr($string, 0, 1));
	return $f . mb_substr($string, 1);
}

/**
 * This singleton implementation offers factory methods.
 */
final class Lemuria
{
	private static ?self $instance = null;

	private Builder $builder;

	private Calendar $calendar;

	private Catalog $catalog;

	private Debut $debut;

	private Game $game;

	private LoggerInterface $log;

	private Orders $orders;

	private Report $report;

	private World $world;

	private Score $score;

	private Registry $registry;

	private Version $version;

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
		self::$instance->debut  = $config->Debut();
		self::$instance->orders = $config->Orders();
		self::$instance->report = $config->Report();
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
			$this->log      = $config->Log()->getLogger();
			$this->builder  = $config->Builder();
			$this->game     = $config->Game();
			$this->calendar = $config->Calendar();
			$this->catalog  = $config->Catalog();
			$this->debut    = $config->Debut();
			$this->world    = $config->World();
			$this->score    = $config->Score();
			$this->registry = $config->Registry();
			$this->version  = new Version();
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
