<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use WebChemistry\Configuration\Factory\ConfigurationFormFactory;
use WebChemistry\Configuration\Generator\TemplateTraitGenerator;
use WebChemistry\Configuration\Model\ConfigurationFields;
use WebChemistry\Configuration\Persister\ConfigurationPersister;
use WebChemistry\Configuration\Persister\DoctrineConfigurationPersister;

final class ConfigurationExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'entity' => Expect::string()->required(),
			'fields' => Expect::listOf(Expect::type(Statement::class)),
			'aliases' => Expect::arrayOf(Expect::string()),
			'forms' => Expect::listOf(Expect::structure([
				'name' => Expect::string()->required(),
				'fields' => Expect::arrayOf(Expect::anyOf(Expect::string(), Expect::arrayOf('mixed'))),
			])->castTo('array'))
		])->castTo('array');
	}

	public function loadConfiguration(): void
	{
		/** @var mixed[] $config */
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('fields'))
			->setFactory(ConfigurationFields::class, [$this->processFields($config['fields'], $config['aliases'])]);

		$builder->addDefinition($this->prefix('factory.form'))
			->setFactory(ConfigurationFormFactory::class, [$config['forms']]);

		$builder->addDefinition($this->prefix('persister'))
			->setType(ConfigurationPersister::class)
			->setFactory(DoctrineConfigurationPersister::class, [$config['entity']]);

		$builder->addDefinition($this->prefix('generator.templateClass'))
			->setFactory(TemplateTraitGenerator::class);
	}

	private function processFields(array $fields, array $aliases): array
	{
		$return = [];

		foreach ($fields as $key => $field) {
			if ($field instanceof Statement && isset($aliases[$field->entity])) {
				$return[$key] = new Statement($aliases[$field->entity], $field->arguments);
			} else {
				$return[$key] = $field;
			}
		}

		return $return;
	}

}
