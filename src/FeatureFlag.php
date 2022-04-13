<?php
declare(strict_types = 1);
namespace Lemuria;

class FeatureFlag
{
	protected bool $isDevelopment = false;

	public function IsDevelopment(): bool {
		return $this->isDevelopment;
	}

	public function setIsDevelopment(bool $isDevelopment): FeatureFlag {
		$this->isDevelopment = $isDevelopment;
		return $this;
	}
}
