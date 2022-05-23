<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class UnknownUuidException extends \InvalidArgumentException
{
	public function __construct(string $uuid) {
		parent::__construct('The UUID ' . $uuid . ' is not registered.');
	}
}
