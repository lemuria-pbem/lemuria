<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * An Entity is a unique item that has an identity.
 */
abstract class Entity implements Identifiable, Serializable
{
	use IdentifiableTrait;
	use SerializableTrait;

	private string $name = '';

	private string $description = '';

	/**
	 * Get a plain data array of the model's data.
	 */
	#[ArrayShape(['id' => 'int', 'name' => 'string', 'description' => 'string'])]
	public function serialize(): array {
		return [
			'id'          => $this->Id()->Id(),
			'name'        => $this->Name(),
			'description' => $this->Description()
		];
	}

	/**
	 * Restore the model's data from serialized data.
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
	#[Pure] public function __toString(): string {
		return $this->Name() . ' [' . $this->Id() . ']';
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array (string=>mixed) &$data
	 */
	protected function validateSerializedData(array &$data): void {
		$this->validate($data, 'id', 'int');
		$this->validate($data, 'name', 'string');
		$this->validate($data, 'description', 'string');
	}
}
