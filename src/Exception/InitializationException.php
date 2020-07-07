<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class InitializationException extends LemuriaException
{
	/**
	 * Create exception.
	 */
	public function __construct() {
		parent::__construct('You have to call the init() method first.');
	}
}
