<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class VersionTooLowException extends \RuntimeException
{
	public function __construct(string $compatibility) {
		parent::__construct('This game requires minimum version ' . $compatibility . '.');
	}
}
