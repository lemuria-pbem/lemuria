<?php
declare(strict_types = 1);
namespace Lemuria;

trait ProfileTrait
{
	private function profileAndLog(string $identifier): static {
		$profiler = Lemuria::Profiler();
		if ($profiler->isEnabled()) {
			$profiler->recordAndLog($identifier);
		}
		return $this;
	}
}
