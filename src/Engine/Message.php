<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Engine\Message\Result;
use Lemuria\Engine\Message\Section;
use Lemuria\Id;
use Lemuria\Model\Domain;
use Lemuria\Serializable;

/**
 * A message created by the game engine.
 */
interface Message extends \Stringable, Serializable
{
	public function Id(): Id;

	public function Result(): Result;

	public function Report(): Domain;

	public function Entity(): Id;

	public function Section(): Section;

	public function setId(Id $id): Message;

	public function __toString(): string;
}
