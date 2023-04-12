<?php
declare (strict_types = 1);
namespace Lemuria\Model\Exception;

use Lemuria\Model\Location;

final class KeyPathException extends ModelException
{
	public function __construct(string $keyPath) {
		$message = 'Key path ' . $keyPath . ' not found in dictionary.';
		parent::__construct($message);
	}
}
