<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Psr\Log\LogLevel;

use Lemuria\Engine\Message\Section;
use Lemuria\Id;
use Lemuria\Model\Domain;
use Lemuria\Serializable;

/**
 * A message created by the game engine.
 */
interface Message extends \Stringable, Serializable
{
	public final const ERROR = LogLevel::ERROR;

	public final const EVENT = LogLevel::WARNING;

	public final const FAILURE = LogLevel::NOTICE;

	public final const SUCCESS = LogLevel::INFO;

	public final const DEBUG = LogLevel::DEBUG;

	public function Id(): Id;

	public function Level(): string;

	public function Report(): Domain;

	public function Entity(): Id;

	public function Section(): Section;

	public function setId(Id $id): Message;

	public function __toString(): string;
}
