<?php
declare (strict_types = 1);
namespace Lemuria;

enum Reorder : int
{
	case After = 1;

	case Before = -1;

	case Flip = 0;
}
