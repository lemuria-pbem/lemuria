<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

use Psr\Log\LogLevel;

enum Result : string
{
	case Success = LogLevel::INFO;

	case Error = LogLevel::ERROR;

	case Failure = LogLevel::NOTICE;

	case Debug = LogLevel::DEBUG;

	case Event = LogLevel::WARNING;
}
