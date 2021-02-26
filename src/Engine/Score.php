<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

interface Score
{
	/**
	 * Load message data into score.
	 */
	public function load(): Score;

	/**
	 * Save message data from score.
	 */
	public function save(): Score;
}
