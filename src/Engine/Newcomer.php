<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

/**
 * An instance of Newcomer is added to the game when a new player begins in Lemuria.
 */
interface Newcomer
{
	/**
	 * Get the UUID.
	 */
	public function Uuid(): string;

	/**
	 * Get the creation time.
	 */
	public function Creation(): int;
}
