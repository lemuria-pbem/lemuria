<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Id;

interface Message
{
	/**
	 * Get the ID.
	 *
	 * @return Id
	 */
	public function Id(): Id;

	/**
	 * Get the report namespace.
	 *
	 * @return int
	 */
	public function Report(): int;

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
