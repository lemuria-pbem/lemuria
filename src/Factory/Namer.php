<?php
declare(strict_types = 1);
namespace Lemuria\Factory;

use Lemuria\Entity;
use Lemuria\Exception\NamerException;
use Lemuria\Identifiable;
use Lemuria\Model\Domain;

/**
 * Defines the interface of a name generator.
 */
interface Namer
{
	/**
	 * Name an entity or get a suitable name for an identifiable or domain.
	 *
	 * @throws NamerException
	 */
	public function name(Domain|Identifiable|Entity $entity): string;
}
