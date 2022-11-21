<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Persister;

interface ConfigurationPersister
{

	public function warmup(): void;

	public function get(string $name): ?string;

	public function persist(string $name, ?string $value): void;

}
