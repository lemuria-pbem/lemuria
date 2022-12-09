<?php
declare(strict_types = 1);
namespace Lemuria\Model;

enum Domain : int
{
	case Party = 1;

	case Unit = 2;

	case Location = 3;

	case Construction = 4;

	case Vessel = 5;

	case Continent = 6;

	case Unicum = 7;

	case Trade = 8;
}
