<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Model;

use OutOfBoundsException;
use WebChemistry\Configuration\Persister\ConfigurationPersister;
use WebChemistry\Configuration\Type\ConfigurationField;

final class ConfigurationFields
{

	/** @var array<string, ConfigurationField> */
	private array $fields = [];

	/**
	 * @param ConfigurationField[] $fields
	 */
	public function __construct(array $fields, ConfigurationPersister $loader)
	{
		foreach ($fields as $field) {
			$this->fields[$field->getId()] = $field;

			$field->setPersister($loader);
		}
	}

	/**
	 * @return array<string, ConfigurationField>
	 */
	public function getFields(): array
	{
		return $this->fields;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getFieldValues(): array
	{
		$values = [];

		foreach ($this->fields as $field) {
			$values[$field->getId()] = $field->getValue();
		}

		return $values;
	}

	public function getField(string $name): ConfigurationField
	{
		return $this->fields[$name] ?? throw new OutOfBoundsException(sprintf('No field %s.', $name));
	}

	public function hasField(string $name): bool
	{
		return isset($this->fields[$name]);
	}

}
