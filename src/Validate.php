<?php
declare(strict_types = 1);
namespace Lemuria;

enum Validate : string
{
	case Array = 'array';

	case ArrayOrNull = '?array';

	case Bool = 'bool';

	case BoolOrNull = '?bool';

	case Float = 'float';

	case FloatOrNull = '?float';

	case Int = 'int';

	case IntOrNull = '?int';

	case String = 'string';

	case StringOrNull = '?string';
}
