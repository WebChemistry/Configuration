<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Factory\Result;

use Nette\Application\UI\Form;

final class ConfigurationForm
{

	public function __construct(
		private string $name,
		private Form $form,
	)
	{
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getForm(): Form
	{
		return $this->form;
	}

}
