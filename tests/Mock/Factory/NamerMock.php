<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Factory;

use Lemuria\Entity;
use Lemuria\Factory\Namer;
use Lemuria\Identifiable;
use Lemuria\Model\Domain;

class NamerMock implements Namer
{
	public function name(Entity|Domain|Identifiable $entity): string {
		return 'Mock';
	}
}
