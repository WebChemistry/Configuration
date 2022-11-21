<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Forms\Container;
use Nette\Forms\Form;
use Nette\Http\FileUpload;
use Utilitte\Asserts\TypeAssert;
use WebChemistry\Configuration\Handler\FileHandler;

/**
 * @extends Field<string|null>
 */
final class StaticImageField extends Field
{

	public function __construct(
		string $id,
		private FileHandler $handler,
		?string $default = null,
	)
	{
		parent::__construct($id, $default ? ltrim($default, '/') : null);
	}

	public function getTemplateValue(): ?string
	{
		$value = $this->getValue();

		if (!$value) {
			return null;
		}

		return $this->handler->getLink($value);
	}

	public function getValue(): ?string
	{
		return parent::getValue();
	}

	public function createInput(?string $caption, Container $container): void
	{
		$container->addUpload($this->id, $caption)
			->addRule(Form::IMAGE)
			->setRequired();
	}

	public function setValue(mixed $value)
	{
		$value = TypeAssert::instanceOrNull($value, FileUpload::class);

		if ($value) {
			$this->handler->upload($value, $this->default);
		}
	}

}
