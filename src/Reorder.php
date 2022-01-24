<?php
declare (strict_types = 1);
namespace Lemuria;

enum Reorder : int
{
	case AFTER = 1;

	case BEFORE = -1;

	case FLIP = 0;
}
