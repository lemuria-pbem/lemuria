<?php
declare(strict_types = 1);
namespace Lemuria\Renderer;

interface PathFactory
{
	/**
	 * Get the path to the file that will contain the rendered object.
	 */
	public function getPath(Writer $writer, mixed $object = null);
}
