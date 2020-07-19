<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Exception\DuplicateIdException;
use Lemuria\Model\Exception\NotRegisteredException;

interface Report
{
	public const PARTY = Catalog::PARTIES;

	public const UNIT = Catalog::UNITS;

	public const LOCATION = Catalog::LOCATIONS;

	public const CONSTRUCTION = Catalog::CONSTRUCTIONS;

	public const VESSEL = Catalog::VESSELS;

	/**
	 * Get the specified message.
	 *
	 * @param Id $id
	 * @param int $namespace
	 * @return Message
	 * @throws NotRegisteredException
	 */
	public function get(Id $id): Message;

	/**
	 * Get all messages of an entity.
	 *
	 * @param Identifiable $identifiable
	 * @return array
	 */
	public function getAll(Identifiable $identifiable): array;

	/**
	 * Load message data into report.
	 *
	 * @return Report
	 */
	public function load(): Report;

	/**
	 * Save game data from report.
	 *
	 * @return Report
	 */
	public function save(): Report;

	/**
	 * Register a message.
	 *
	 * @param Message $message
	 * @return Report
	 * @throws DuplicateIdException
	 */
	public function register(Message $message): Report;

	/**
	 * Reserve the next ID.
	 *
	 * @return Id
	 */
	public function nextId(): Id;
}
