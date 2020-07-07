<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class InitializationException extends LemuriaException
{
	/**
	 * @param \Throwable $previous
	 */
	public function __construct(\Throwable $previous) {
		parent::__construct('You have to call the load() method first.', $previous);
	}
}
