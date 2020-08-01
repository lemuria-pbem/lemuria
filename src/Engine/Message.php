<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Psr\Log\LogLevel;

use Lemuria\Id;
use Lemuria\Serializable;

interface Message extends Serializable
{
	public const ERROR = LogLevel::ERROR;

	public const EVENT = LogLevel::WARNING;

	public const FAILURE = LogLevel::NOTICE;

	public const SUCCESS = LogLevel::INFO;

	public const DEBUG = LogLevel::DEBUG;

	/**
	 * Get the ID.
	 *
	 * @return Id
	 */
	public function Id(): Id;

	/**
	 * @return string
	 */
	public function Level(): string;

	/**
	 * Get the report namespace.
	 *
	 * @return int
	 */
	public function Report(): int;

	/**
	 * Get the entity ID.
	 *
	 * @return Id
	 */
	public function Entity(): Id;

	/**
	 * Set the ID.
	 *
	 * @param Id $id
	 * @return Message
	 */
	public function setId(Id $id): Message;

	/**
	 * Get the message text.
	 *
	 * @return string
	 */
	public function __toString(): string;
}
