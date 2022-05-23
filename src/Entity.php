<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\IdException;
use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Model\NamedId;

/**
 * An Entity is a unique item that has an identity.
 */
abstract class Entity implements \Stringable, Identifiable, Serializable
{
	use IdentifiableTrait;
	use SerializableTrait;

	private string $name = '';

	private string $description = '';

	/**
	 * @throws IdException
	 */
	public static function from(string $entity): NamedId {
		if (preg_match('/^([^\[]+) \[(' . Id::REGEX . ')]$/', $entity, $matches) === 1) {
			$id   = Id::fromId($matches[2]);
			$name = $matches[1];
			return new NamedId($id, $name);
		}
		throw new IdException($entity);
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array<string, mixed>
	 */
	public function serialize(): array {
		return [
			'id'          => $this->Id()->Id(),
			'name'        => $this->Name(),
			'description' => $this->Description()
		];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array<string, mixed> $data
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		return $this->setId(new Id($data['id']))->setName($data['name'])->setDescription($data['description']);
	}

	public function Name(): string {
		return $this->name;
	}

	public function Description(): string {
		return $this->description;
	}

	public function setName(string $name): Entity {
		$this->name = $name;
		return $this;
	}

	public function setDescription(string $description): Entity {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get name and ID.
	 */
	public function __toString(): string {
		return $this->Name() . ' [' . $this->Id() . ']';
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array<string, mixed> $data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(array &$data): void {
		$this->validate($data, 'id', 'int');
		$this->validate($data, 'name', 'string');
		$this->validate($data, 'description', 'string');
	}
}
