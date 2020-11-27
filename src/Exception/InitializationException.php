<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class InitializationException extends LemuriaException
{
	#[Pure] public function __construct() {
		parent::__construct('You have to call the init() method first.');
	}
}
