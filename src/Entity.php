<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * An Entity is a unique item that has an identity.
 */
abstract class Entity implements Identifiable, Serializable
{
	use IdentifiableTrait;
	use SerializableTrait;

	/**
	 * @var string
	 */
	private string $name = '';

	/**
	 * @var string
	 */
	private string $description = '';

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array
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
	 * @param array $data
	 * @return Serializable
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		return $this->setId(new Id($data['id']))->setName($data['name'])->setDescription($data['description']);
	}

	/**
	 * Get the name.
	 *
	 * @return string
	 */
	public function Name(): string {
		return $this->name;
	}

	/**
	 * Get the description.
	 *
	 * @return string
	 */
	public function Description(): string {
		return $this->description;
	}

	/**
	 * Set the name.
	 *
	 * @param string $name
	 * @return Entity
	 */
	public function setName(string $name): Entity {
		$this->name = $name;
		return $this;
	}

	/**
	 * Set the description.
	 *
	 * @param string $description
	 * @return Entity
	 */
	public function setDescription(string $description): Entity {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get name and ID.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->Name() . ' [' . $this->Id() . ']';
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array (string=>mixed) &$data
	 */
	protected function validateSerializedData(&$data): void {
		$this->validate($data, 'id', 'int');
		$this->validate($data, 'name', 'string');
		$this->validate($data, 'description', 'string');
	}
}
