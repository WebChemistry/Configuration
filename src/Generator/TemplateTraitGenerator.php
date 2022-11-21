<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Generator;

use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\PhpFile;
use Nette\Utils\Type;
use ReflectionClass;
use WebChemistry\Configuration\Model\ConfigurationFields;

final class TemplateTraitGenerator
{

	public function __construct(
		private ConfigurationFields $fields,
	)
	{
	}

	public function generate(string $className = 'ConfigurationFieldsTrait'): PhpFile
	{
		$file = new PhpFile();
		$file->setStrictTypes();

		$base = $file;

		if ($namespace = Helpers::extractNamespace($className)) {
			$base = $file->addNamespace($namespace);
		}

		$trait = $base->addTrait(Helpers::extractShortName($className));

		foreach ($this->fields->getFields() as $field) {
			$reflection = new ReflectionClass($field);
			$method = $reflection->getMethod('getTemplateValue');

			$property = $trait->addProperty($field->getId())
				->setType((string) Type::fromReflection($method));

			$doc = $method->getDocComment();
			if ($doc && preg_match("#\\*\s+@return\\s+([^\n\\*]+)#", $doc, $matches)) {
				$property->addComment(sprintf('@var %s', trim($matches[1])));
			}
		}

		return $file;
	}

}
