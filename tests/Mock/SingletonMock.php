<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Lemuria\Singleton;
use Lemuria\SingletonTrait;

class SingletonMock implements Singleton
{
	use SingletonTrait;
}
