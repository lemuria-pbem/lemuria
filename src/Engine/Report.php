<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Engine\Exception\NotRegisteredException;
use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Exception\DuplicateIdException;
use Lemuria\Model\Catalog;

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
	 * @throws NotRegisteredException
	 */
	public function get(Id $id): Message;

	/**
	 * Get all messages of an entity.
	 */
	public function getAll(Identifiable $identifiable): array;

	/**
	 * Load message data into report.

	 */
	public function load(): Report;

	/**
	 * Save game data from report.
	 */
	public function save(): Report;

	/**
	 * Register a message.
	 *
	 * @throws DuplicateIdException
	 */
	public function register(Message $message): Report;

	/**
	 * Reserve the next ID.
	 */
	public function nextId(): Id;
}
