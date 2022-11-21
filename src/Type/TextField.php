<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Type;

use Nette\Application\UI\Form;
use Nette\Forms\Container;

class TextField extends StringField
{

	public function createInput(?string $caption, Container $container): void
	{
		$container->addTextArea($this->getId(), $caption)
			->setDefaultValue($this->getValue())
			->setRequired((bool) $this->default);
	}

}
