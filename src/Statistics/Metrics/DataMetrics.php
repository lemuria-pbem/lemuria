<?php
declare(strict_types = 1);

namespace Lemuria\Statistics\Metrics;

use Lemuria\Statistics\Data;

class DataMetrics extends BaseMetrics
{
	protected Data $data;

	public function Data(): ?Data {
		return $this->data;
	}

	public function setData(Data $data): DataMetrics {
		$this->data = $data;
		return $this;
	}
}
