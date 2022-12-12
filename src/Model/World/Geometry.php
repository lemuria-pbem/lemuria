<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

enum Geometry : string
{
	case Flat = 'flat';

	case Spherical = 'spherical';
}
