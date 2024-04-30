<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\SerializableTrait;
use Lemuria\Validate;

abstract class Annals extends EntitySet
{
	use SerializableTrait;

	protected final const string ENTITIES = 'entities';

	protected final const string ROUNDS = 'rounds';

	/**
	 * @var array<int, int>
	 */
	private array $round = [];

	public function __construct() {
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
	 * @return array<string, array>
	 */
	public function serialize(): array {
		$entities = [];
		$rounds   = [];
		foreach ($this->round as $id => $round) {
			$entities[] = $id;
			$rounds[]   = $round;
		}
		return [self::ENTITIES => $entities, self::ROUNDS => $rounds];
	}

	/**
	 * @param array<string, array> $data
	 */
	public function unserialize(array $data): static {
		$this->validateSerializedData($data);
		if ($this->count() > 0) {
			$this->clear();
		}

		$entities = array_values($data[self::ENTITIES]);
		$rounds   = array_values($data[self::ROUNDS]);
		$n        = count($entities);
		if (count($rounds) !== $n) {
			throw new UnserializeException(
				'Mismatch of ' . self::ENTITIES . ' and ' . self::ROUNDS . ' count.'
			);
		}

		for ($i = 0; $i < $n; $i++) {
			$this->addEntity(new Id($entities[$i]), $rounds[$i]);
		}
		return $this;
	}

	/**
	 * Clear the set.
	 */
	public function clear(): static {
		$this->round = [];
		return parent::clear();
	}

	public function fill(EntitySet $set): static {
		if ($set instanceof Annals) {
			$this->round = $set->round;
			return parent::fill($set);
		}
		throw new \InvalidArgumentException();
	}

	protected function addEntity(Id $id, ?int $round = null): void {
		parent::addEntity($id);
		$this->round[$id->Id()] = $round ?: Lemuria::Calendar()->Round();
	}

	/**
	 * Not implemented.
	 *
	 * @throws LemuriaException
	 */
	protected function removeEntity(Id $id): never {
		throw new LemuriaException('Removing entities is intentionally not implemented.');
	}

	/**
	 * @param array<string, array> $data
	 */
	protected function validateSerializedData(array $data): void {
		$this->validate($data, self::ENTITIES, Validate::Array);
		$this->validate($data, self::ROUNDS, Validate::Array);
	}

	protected function getRound(int $id): int {
		if (!isset($this->round[$id])) {
			throw new LemuriaException('No round set for ID ' . $id . '.');
		}
		return $this->round[$id];
	}
}
