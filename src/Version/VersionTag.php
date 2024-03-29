<?php
declare(strict_types = 1);
namespace Lemuria\Version;

final readonly class VersionTag implements \Stringable
{
	public function __construct(public string $name, public string $version) {
	}

	public function __toString(): string {
		return $this->name . ' ' . $this->version;
	}
}
