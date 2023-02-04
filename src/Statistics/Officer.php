<?php
declare(strict_types = 1);
namespace Lemuria\Statistics;

/**
 * A statistics officer is responsible for handling statistics calculation messages.
 */
interface Officer
{
	public function Id(): int;

	/**
	 * @return array<string>
	 */
	public function Subjects(): array;

	public function process(Metrics $message): void;

	public function close(): void;
}
