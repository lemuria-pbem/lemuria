<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\InitializationException;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use Lemuria\Model\Builder;
use Lemuria\Model\Calendar;
use Lemuria\Model\Catalog;
use Lemuria\Model\Config;
use Lemuria\Model\Game;
use Lemuria\Model\World;
use function PHPUnit\Framework\throwException;

/**
 * Return the class name of an object without it's namespace.
 *
 * @param object|string $object
 * @return string
 */
function getClass($object): string {
	$class = is_object($object) ? get_class($object) : $object;
	$i     = strripos($class, '\\');
	return $i > 0 ? substr($class, $i + 1) : $class;
}

/**
 * Checks if a string has a given prefix.
 *
 * @param string $prefix
 * @param mixed $subject
 * @return bool
 */
function hasPrefix(string $prefix, $subject): bool {
	return $prefix === '' ? true : strpos((string)$subject, $prefix) === 0;
}

/**
 * Check if given string represents an integer.
 *
 * @param string $subject
 * @return bool
 */
function isInt(string $subject): bool {
	return (string)(int)$subject === $subject;
}

/**
 * The mathematical signum function.
 *
 * @param mixed $number Number argument.
 * @return int 1 if argument is greater or equal to zero, -1 otherwise.
 */
function sign($number): int {
	return $number >= 0 ? 1 : -1;
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

	private Game $game;

	private LoggerInterface $log;

	private World $world;

	/**
	 * Get the builder.
	 *
	 * @return Builder
	 * @throws InitializationException
	 */
	public static function Builder(): Builder {
		return self::getInstance()->builder;
	}

	/**
	 * Get the Calendar.
	 *
	 * @return Calendar
	 * @throws InitializationException
	 */
	public static function Calendar(): Calendar {
		return self::getInstance()->calendar;
	}

	/**
	 * Get the Catalog.
	 *
	 * @return Catalog
	 * @throws InitializationException
	 */
	public static function Catalog(): Catalog {
		return self::getInstance()->catalog;
	}

	/**
	 * Get the Game.
	 *
	 * @return Game
	 * @throws InitializationException
	 */
	public static function Game(): Game {
		return self::getInstance()->game;
	}

	/**
	 * Get the log.
	 *
	 * @return LoggerInterface
	 */
	public static function Log(): LoggerInterface {
		return self::getInstance()->log;
	}

	/**
	 * Get the World.
	 *
	 * @return World
	 * @throws InitializationException
	 */
	public static function World(): World {
		return self::getInstance()->world;
	}

	/**
	 * @param Config $config
	 */
	public static function init(Config $config): void {
		self::$instance = new self($config);
	}

	/**
	 * Init Lemuria for the current Game.
	 */
	public static function load(): void {
		self::Calendar()->load();
		self::Catalog()->load();
		self::World()->load();
	}

	/**
	 * Save the current Game.
	 */
	public static function save(): void {
		self::Calendar()->save();
		self::Catalog()->save();
		self::World()->save();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return Lemuria
	 */
	private static function getInstance(): Lemuria {
		if (!self::$instance) {
			throw new InitializationException();
		}
		return self::$instance;
	}

	/**
	 * Private constructor for singleton.
	 *
	 * @param Config $config
	 */
	private function __construct(Config $config) {
		try {
			$this->log      = $this->createLog($config->getPathToLog());
			$this->builder  = $config->Builder();
			$this->calendar = $config->Calendar();
			$this->catalog  = $config->Catalog();
			$this->game     = $config->Game();
			$this->world    = $config->World();
		} catch (\Exception $e) {
			die((string)$e);
		}
	}

	/**
	 * Create the Log.
	 *
	 * @param string $logPath
	 * @return LoggerInterface
	 * @throws \Exception
	 */
	private function createLog(string $logPath): LoggerInterface {
		$logDir = dirname($logPath);
		if (!file_exists($logDir)) {
			@mkdir($logDir, 0775, true);
		}
		file_exists($logPath) ? file_put_contents($logPath, '') : touch($logPath);
		$logFile = new StreamHandler($logPath);

		$logConsole = new StreamHandler('php://stdout', LogLevel::INFO);

		$log = new Logger('lemuria');
		$log->pushHandler($logFile);
		$log->pushHandler($logConsole);
		ErrorHandler::register($log);

		return $log;
	}
}
