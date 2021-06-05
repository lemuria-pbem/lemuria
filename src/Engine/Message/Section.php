<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

final class Section
{
	public const EVENT = 0;

	public const ERROR = 1;

	public const BATTLE = 2;

	public const ECONOMY = 3;

	public const MAGIC = 4;

	public const MAIL = 5;

	public const MOVEMENT = 6;

	public const PRODUCTION = 7;

	public const STUDY = 8;

	private function __construct() {
	}
}
