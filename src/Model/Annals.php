<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Id;
use Lemuria\Lemuria;

abstract class Annals extends EntitySet
{
	/**
	 * @var array(int=>int)
	 */
	private array $round = [];

	/**
	 * Add an entity's ID to the set.
	 */
	protected function addEntity(Id $id): void {
		parent::addEntity($id);
		$this->round[$id->Id()] = Lemuria::Calendar()->Round();
	}

	protected function getRound(int $id): int {
		if (!isset($this->round[$id])) {
			throw new LemuriaException('No round set for ID ' . $id . '.');
		}
		return $this->round[$id];
	}
}
