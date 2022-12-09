<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

enum Section : int
{
	case Battle = 1;

	case Economy = 2;

	case Error = 3;

	case Event = 4;

	case Magic = 5;

	case Mail = 6;

	case Movement = 7;

	case Production = 8;

	case Study = 9;
}
