<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * Defines reordering constants.
 */
interface Reorder
{
	const AFTER = 1;

	const BEFORE = -1;

	const FLIP = 0;
}
