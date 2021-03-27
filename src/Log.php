<?php
declare(strict_types = 1);
namespace Lemuria;

use Psr\Log\LoggerInterface;

interface Log
{
	public function getLogger(): LoggerInterface;
}
