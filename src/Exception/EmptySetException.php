<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class EmptySetException extends \RuntimeException
{
	#[Pure] public function __construct() {
		parent::__construct('Entity set is empty.');
	}
}
