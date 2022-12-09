<?php
declare(strict_types = 1);
namespace Lemuria;

enum SortMode
{
	case ById;

	case ByParty;

	case ByResidents;

	case ByType;

	case NorthToSouth;
}
