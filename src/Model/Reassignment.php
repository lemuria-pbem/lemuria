<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Id;
use Lemuria\Identifiable;

/**
 * Classes that need to react on reassignment of an entity's ID must implement this interface.
 */
interface Reassignment
{
	public function reassign(Id $oldId, Identifiable $identifiable): void;

	public function remove(Identifiable $identifiable): void;
}
