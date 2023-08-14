<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Engine\Exception\NotRegisteredException;
use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Exception\DuplicateIdException;

interface Report
{
	/**
	 * Get the specified message.
	 *
	 * @throws NotRegisteredException
	 */
	public function get(Id $id): Message;

	/**
	 * Get all messages of an entity.
	 *
	 * @return array<Message>
	 */
	public function getAll(Identifiable $entity): array;

	/**
	 * Load message data into report.
	 */
	public function load(): static;

	/**
	 * Save message data from report.
	 */
	public function save(): static;

	/**
	 * Clear all messages in preparation for a new turn.
	 */
	public function clear(): static;

	/**
	 * Register a message.
	 *
	 * @throws DuplicateIdException
	 */
	public function register(Message $message): static;

	/**
	 * Reserve the next ID.
	 */
	public function nextId(): Id;
}
