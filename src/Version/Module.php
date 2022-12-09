<?php
declare(strict_types = 1);
namespace Lemuria\Version;

enum Module : string
{
	case Base = 'base';

	case Engine = 'engine';

	case Game = 'game';

	case Model = 'model';

	case Renderers = 'renderers';

	case Statistics = 'statistics';
}
