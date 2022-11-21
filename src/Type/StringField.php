<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Forms\Container;
use Utilitte\Asserts\TypeAssert;

/**
 * @extends Field<string|null>
 */
class StringField extends Field
{

	public function __construct(string $id, ?string $default = null)
	{
		parent::__construct($id, $default);
	}

	public function getTemplateValue(): ?string
	{
		return $this->getValue();
	}

	public function getValue(): ?string
	{
		return parent::getValue();
	}

	public function isEmpty(): bool
	{
		return !$this->value;
	}

	public function createInput(?string $caption, Container $container): void
	{
		$container->addText($this->id, $caption)
			->setRequired((bool) $this->default)
			->setNullable()
			->setDefaultValue($this->getValue());
	}

	public function setValue(mixed $value)
	{
		parent::setValue(TypeAssert::stringOrNull($value));
	}

}
