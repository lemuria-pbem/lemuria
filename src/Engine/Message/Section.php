<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

enum Section : int
{
	case BATTLE = 1;

	case ECONOMY = 2;

	case ERROR = 3;

	case EVENT = 4;

	case MAGIC = 5;

	case MAIL = 6;

	case MOVEMENT = 7;

	case PRODUCTION = 8;

	case STUDY = 9;
}
