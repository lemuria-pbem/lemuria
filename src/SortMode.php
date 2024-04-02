<?php
declare(strict_types = 1);
namespace Lemuria;

enum SortMode
{
	case Alphabetically;

	case ByCount;

	case ById;

	case ByParty;

	case ByResidents;

	case ByRealm;

	case BySize;

	case ByType;

	case NorthToSouth;
}
