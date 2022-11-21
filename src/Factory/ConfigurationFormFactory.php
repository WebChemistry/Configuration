<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Factory;

use Nette\Application\UI\Form;
use WebChemistry\Configuration\Factory\Result\ConfigurationForm;
use WebChemistry\Configuration\Model\ConfigurationFields;
use WebChemistry\Configuration\Persister\ConfigurationPersister;

final class ConfigurationFormFactory
{

	/**
	 * @param array<array{name: string, fields: array<string, string|mixed[]>}> $forms
	 */
	public function __construct(
		private array $forms,
		private ConfigurationFields $configurationFields,
		private ConfigurationPersister $configurationLoader,
	)
	{
	}

	/**
	 * @param callable(): Form $factory
	 * @return ConfigurationForm[]
	 */
	public function createForms(callable $factory): array
	{
		$this->configurationLoader->warmup();

		$forms = [];
		foreach ($this->forms as $formOptions) {
			$form = $factory();

			foreach ($formOptions['fields'] as $name => $options) {
				if (is_string($options)) {
					$caption = $options;
					$options = [];
				} else {
					$caption = $options['caption'] ?? null;

					unset($options['caption']);
				}

				$this->configurationFields->getField($name)
					->createInput(
						$caption,
						$form,
						$options,
					);
			}

			$form->addSubmit('send');

			$form->onSuccess[] = fn (array $values) => $this->onSuccess($values);

			$forms[] = new ConfigurationForm($formOptions['name'], $form);
		}

		return $forms;
	}

	/**
	 * @param mixed[] $values
	 */
	private function onSuccess(array $values): void
	{
		foreach ($values as $name => $value) {
			$this->configurationFields->getField($name)
				->setValue($value);
		}
	}

}
