<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Utilitte\Asserts\TypeAssert;
use WebChemistry\Configuration\Persister\ConfigurationPersister;

/**
 * @template T
 */
abstract class Field implements ConfigurationField
{

	private bool $isValueLoaded = false;

	/** @var T */
	protected mixed $value;

	protected ConfigurationPersister $persister;

	/**
	 * @param T $default
	 */
	public function __construct(
		protected string $id,
		protected mixed $default,
	)
	{
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function isEmpty(): bool
	{
		return $this->value === null;
	}

	/**
	 * @return T
	 */
	public function getValue(): mixed
	{
		if (!$this->isValueLoaded) {
			$value = $this->persister->get($this->id);

			$this->value = $value === null ? $this->default : $this->unserializeValue($value);
		}
		
		return $this->value;
	}

	/**
	 * @param T $value
	 */
	public function setValue(mixed $value)
	{
		$this->isValueLoaded = true;
		$this->value = $value;

		$this->persister->persist($this->id, $this->serializeValue($value));
	}

	/**
	 * @param T $value
	 */
	protected function serializeValue(mixed $value): ?string
	{
		return TypeAssert::stringOrNull($value);
	}

	/**
	 * @param string $value
	 * @return T
	 */
	protected function unserializeValue(string $value): mixed
	{
		return $value;
	}

	protected function persistValue(?string $value): void
	{
		$this->persister->persist($this->id, $value);
	}

	public function setPersister(ConfigurationPersister $persister): void
	{
		$this->persister = $persister;
	}

}
