<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Forms\Container;
use Nette\Utils\Json;
use Utilitte\Asserts\TypeAssert;
use WebChemistry\Configuration\Persister\VoidPersister;

/**
 * @extends Field< array<int, array<string, mixed>> >
 */
class StaticCollectionField extends Field implements ConfigurationFieldWithInputOptions
{

	/**
	 * @param ConfigurationField[] $fields
	 */
	public function __construct(
		string $id,
		private int $count,
		private array $fields,
	)
	{
		parent::__construct($id, []);

		foreach ($this->fields as $field) {
			$field->setPersister(new VoidPersister());
		}
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function getTemplateValue(): array
	{
		return parent::getValue();
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public function getValue(): array
	{
		return parent::getValue();
	}

	public function setValue(mixed $value)
	{
		$args = [];

		foreach (TypeAssert::array($value) as $values) {
			$collection = [];

			$filled = false;
			foreach ($this->fields as $field) {
				if (!array_key_exists($field->getId(), $values)) {
					continue;
				}

				$field->setValue($values[$field->getId()]);

				if (!$field->isEmpty()) {
					$filled = true;
				}

				$collection[$field->getId()] = $field->getValue();
			}

			if ($filled) {
				$args[] = $collection;
			}
		}

		parent::setValue($args);
	}

	/**
	 * @param array<int, array<string, mixed>> $value
	 */
	protected function serializeValue(mixed $value): ?string
	{
		return Json::encode($value);
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	protected function unserializeValue(string $value): array
	{
		return Json::decode($value, Json::FORCE_ARRAY);
	}

	/**
	 * @param mixed[] $options
	 */
	public function createInput(?string $caption, Container $container, array $options = []): void
	{
		$values = $this->getValue();

		$container = $container->addContainer($this->getId());

		for ($i = 1; $i <= $this->count; $i++) {
			$parent = $container->addContainer($i);

			foreach ($this->fields as $field) {
				if (isset($options['captionTemplates'][$field->getId()])) {
					$caption = sprintf($options['captionTemplates'][$field->getId()], $i);
				} else {
					$caption = ($options['captions'][$field->getId()] ?? $field->getId()) . ' - ' . $i;
				}

				$field->createInput($caption, $parent);
			}

			$parent->setDefaults($values[$i - 1] ?? []);
		}
	}

}
