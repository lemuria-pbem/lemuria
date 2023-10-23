<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

class IniFileException extends FileException {

	public function __construct(string $message, int $line) {
		parent::__construct('INI format error in line ' . $line . ': ' . $message);
	}
}
