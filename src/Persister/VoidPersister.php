<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Persister;

final class VoidPersister implements ConfigurationPersister
{

	public function warmup(): void
	{
		// void
	}

	public function get(string $name): ?string
	{
		return null;
	}

	public function persist(string $name, ?string $value): void
	{
		// void
	}

}
