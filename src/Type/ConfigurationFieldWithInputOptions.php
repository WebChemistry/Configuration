<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Forms\Container;

interface ConfigurationFieldWithInputOptions extends ConfigurationField
{

	/**
	 * @param mixed[] $options
	 */
	public function createInput(?string $caption, Container $container, array $options = []): void;

}
