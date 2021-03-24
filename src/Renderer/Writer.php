<?php
declare(strict_types = 1);
namespace Lemuria\Renderer;

use Lemuria\Engine\Message\Filter;
use Lemuria\Id;

interface Writer
{
	/**
	 * Set a message filter to prevent output of matching messages.
	 */
	public function setFilter(Filter $filter): Writer;

	/**
	 * Render a party's report.
	 */
	public function render(Id $party): Writer;
}
