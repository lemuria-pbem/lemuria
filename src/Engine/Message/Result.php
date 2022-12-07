<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

use Psr\Log\LogLevel;

enum Result : string
{
	case SUCCESS = LogLevel::INFO;

	case ERROR = LogLevel::ERROR;

	case FAILURE = LogLevel::NOTICE;

	case DEBUG = LogLevel::DEBUG;

	case EVENT = LogLevel::WARNING;
}
