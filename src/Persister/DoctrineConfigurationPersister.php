<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Persister;

use Doctrine\ORM\EntityManagerInterface;
use WebChemistry\Configuration\Entity\ConfigurationEntity;
use WebChemistry\Configuration\Model\ConfigurationFields;

final class DoctrineConfigurationPersister implements ConfigurationPersister
{

	/** @var array<string, string|null> */
	private array $cache = [];

	public function __construct(
		private string $entity,
		private EntityManagerInterface $em,
	)
	{
	}

	public function warmup(): void
	{
		$this->em->getRepository($this->entity)->findAll();
	}

	public function get(string $name): ?string
	{
		if (!array_key_exists($name, $this->cache)) {
			/** @var ConfigurationEntity|null $entity */
			$entity = $this->em->find($this->entity, $name);

			$this->cache[$name] = $entity?->getContent();
		}

		return $this->cache[$name];
	}

	public function persist(string $name, ?string $value): void
	{
		/** @var ConfigurationEntity $entity */
		$entity = $this->em->find($this->entity, $name) ?? new ($this->entity)($name, $value);
		$entity->setContent($value);

		$this->em->persist($entity);
		$this->em->flush();

		$this->cache[$name] = $value;
	}

}
