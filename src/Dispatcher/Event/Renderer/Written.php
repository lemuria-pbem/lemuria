<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher\Event\Renderer;

use Lemuria\Dispatcher\Event;
use Lemuria\Id;
use Lemuria\Renderer\Writer;

readonly class Written extends Event
{
	public function __construct(public Writer $writer, public Id $entity, public string $path) {
		parent::__construct();
	}
}
