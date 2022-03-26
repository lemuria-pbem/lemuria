<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Model\Property;

trait TenantTrait
{
	private array $tenants = [];

	protected function property(Identifiable $tenant): Property {
		$contract = $this->contract($tenant);
		if (!isset($this->tenants[$contract])) {
			$this->tenants[$contract] = new Property();
		}
		return $this->tenants[$contract];
	}

	private function contract(Identifiable $tenant): string {
		return $tenant->Catalog()->value . '-' . $tenant->Id();
	}
}
