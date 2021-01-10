<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;

abstract class Annals extends EntitySet
{
	use SerializableTrait;

	/**
	 * @var array(int=>int)
	 */
	private array $round = [];

	#[Pure] public function __construct() {
		parent::__construct();
	}

	/**
	 * Not implemented.
	 *
	 * @param int $offset
	 * @throws LemuriaException
	 */
	public function offsetUnset(mixed $offset): void {
		throw new LemuriaException('Unsetting via ArrayAccess is intentionally not implemented.');
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return int[]
	 * @noinspection PhpPureFunctionMayProduceSideEffectsInspection
	 */
	#[ArrayShape(['entities' => "array", 'rounds' => "array"])]
	#[Pure]
	public function serialize(): array {
		$entities = [];
		$rounds   = [];
		foreach ($this->round as $id => $round) {
			$entities[] = $id;
			$rounds[]   = $round;
		}
		return ['entities' => $entities, 'rounds' => $rounds];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array(array) $data
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		if ($this->count() > 0) {
			$this->clear();
		}

		$entities = array_values($data['entities']);
		$regions  = array_values($data['rounds']);
		$n        = count($entities);
		if (count($regions) !== $n) {
			throw new UnserializeException('Mismatch of entities and rounds count.');
		}

		for ($i = 0; $i < $n; $i++) {
			$this->addEntity(new Id($entities[$i]), $regions[$i]);
		}
		return $this;
	}

	/**
	 * Clear the set.
	 */
	public function clear(): EntitySet {
		$this->round = [];
		return parent::clear();
	}

	protected function addEntity(Id $id, int $round = null): void {
		parent::addEntity($id);
		$this->round[$id->Id()] = $round ? $round : Lemuria::Calendar()->Round();
	}

	/**
	 * Not implemented.
	 *
	 * @throws LemuriaException
	 */
	protected function removeEntity(Id $id): void {
		throw new LemuriaException('Removing entities is intentionally not implemented.');
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array(string=>mixed) $data
	 */
	protected function validateSerializedData(array &$data): void {
		$this->validate($data, 'entities', 'array');
		$this->validate($data, 'rounds', 'array');
	}

	protected function getRound(int $id): int {
		if (!isset($this->round[$id])) {
			throw new LemuriaException('No round set for ID ' . $id . '.');
		}
		return $this->round[$id];
	}
}
