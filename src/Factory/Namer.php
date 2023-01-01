<?php
declare(strict_types = 1);
namespace Lemuria\Factory;

use Lemuria\Exception\NamerException;
use Lemuria\Identifiable;
use Lemuria\Model\Domain;

/**
 * Defines the interface of a name generator.
 */
interface Namer
{
	/**
	 * Name an entity.
	 *
	 * @throws NamerException
	 */
	public function name(Domain|Identifiable $entity): string;
}
