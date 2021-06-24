<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class UnknownUuidException extends \InvalidArgumentException
{
	#[Pure] public function __construct(string $uuid) {
		parent::__construct('The UUID ' . $uuid . ' is not registered.');
	}
}
