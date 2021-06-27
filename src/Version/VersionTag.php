<?php
declare(strict_types = 1);
namespace Lemuria\Version;

final class VersionTag
{
	public function __construct(public string $name, public string $version) {
	}
}
