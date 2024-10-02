<?php
declare(strict_types = 1);
namespace Lemuria\Renderer;

use Lemuria\Dispatcher\Attribute\Emit;
use Lemuria\Dispatcher\Event\Renderer\Written;
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
	public function setFilter(Filter $filter): static;

	/**
	 * Render an entities' report.
	 */
	#[Emit(Written::class)]
	public function render(Id $entity): static;

	/**
	 * Get the version of the package of this writer.
	 */
	public function getVersion(): VersionTag;
}
