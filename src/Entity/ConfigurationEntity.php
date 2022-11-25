<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Entity;

interface ConfigurationEntity
{

	public function getId(): string;

	public function setContent(?string $content);

	public function getContent(): ?string;

}
