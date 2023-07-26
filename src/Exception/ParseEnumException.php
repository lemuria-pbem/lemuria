<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use function Lemuria\getClass;

class ParseEnumException extends \DomainException
{
	public function __construct(?string $enum = null, ?string $name = null, ?\Throwable $previous = null) {
		$message  = 'Could not create ' . ($enum ? getClass($enum) : '');
		$message .= 'enum' . ($name ? ' with name ' . $name : '') . '.';
		parent::__construct($message, previous: $previous);
	}
}
