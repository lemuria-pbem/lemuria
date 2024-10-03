<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher\Event\FastCache;

use Lemuria\Cache\FastCache;
use Lemuria\Dispatcher\Event;

final readonly class Persisting extends Event
{
	public function __construct(public FastCache $cache) {
		parent::__construct();
	}
}
