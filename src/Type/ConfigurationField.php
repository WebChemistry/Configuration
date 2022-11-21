<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Forms\Container;
use WebChemistry\Configuration\Persister\ConfigurationPersister;

/**
 * @template T
 */
interface ConfigurationField
{

	public function getId(): string;

	public function isEmpty(): bool;

	public function getTemplateValue();

	/**
	 * @return T
	 */
	public function getValue();

	/**
	 * @param T $value
	 */
	public function setValue(mixed $value);

	public function createInput(?string $caption, Container $container): void;

	public function setPersister(ConfigurationPersister $persister);

}
