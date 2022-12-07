<?php
declare(strict_types = 1);
namespace Lemuria\Version;

enum Module : string
{
	case BASE = 'base';

	case ENGINE = 'engine';

	case GAME = 'game';

	case MODEL = 'model';

	case RENDERERS = 'renderers';

	case STATISTICS = 'statistics';
}
