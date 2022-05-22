<?php
declare(strict_types = 1);
namespace Lemuria\Renderer;

use Lemuria\Engine\Message\Filter;
use Lemuria\Id;
use Lemuria\Version\VersionTag;

interface Writer
{
	/**
	 * Set the path factory to use.
	 */
	public function __construct(PathFactory $pathFactory);

	/**
	 * Set a filter for content output.
	 */
	public function setFilter(Filter $filter): Writer;

	/**
	 * Render an entities' report.
	 */
	public function render(Id $entity): Writer;

	/**
	 * Get the version of the package of this writer.
	 */
	public function getVersion(): VersionTag;
}
