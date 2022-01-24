<?php
declare(strict_types = 1);
namespace Lemuria\Model;

enum Domain : int
{
	case PARTY = 100;

	case UNIT = 200;

	case LOCATION = 300;

	case CONSTRUCTION = 400;

	case VESSEL = 500;

	case CONTINENT = 600;
}
