<?php
declare(strict_types = 1);
namespace Lemuria\Renderer;

use Lemuria\Id;

interface Writer
{
	public function render(Id $party): Writer;
}
