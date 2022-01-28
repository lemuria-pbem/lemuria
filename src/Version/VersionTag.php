<?php
declare(strict_types = 1);
namespace Lemuria\Version;

final class VersionTag implements \Stringable
{
	public function __construct(public readonly string $name, public readonly string $version) {
	}

	public function __toString(): string {
		return $this->name . ' ' . $this->version;
	}
}
