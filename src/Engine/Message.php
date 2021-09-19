<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Psr\Log\LogLevel;

use Lemuria\Id;
use Lemuria\Serializable;

/**
 * A message created by the game engine.
 */
interface Message extends \Stringable, Serializable
{
	public const ERROR = LogLevel::ERROR;

	public const EVENT = LogLevel::WARNING;

	public const FAILURE = LogLevel::NOTICE;

	public const SUCCESS = LogLevel::INFO;

	public const DEBUG = LogLevel::DEBUG;

	public function Id(): Id;

	public function Level(): string;

	public function Report(): int;

	public function Entity(): Id;

	public function Section(): int;

	public function setId(Id $id): Message;

	public function __toString(): string;
}
