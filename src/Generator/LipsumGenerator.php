<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Generator;

final class LipsumGenerator
{

	private static string $lipsum;

	public static function generate(?int $length = 0): string
	{
		if (!isset(self::$lipsum)) {
			self::$lipsum = trim(file_get_contents(__DIR__ . '/source/lipsum.txt'));
		}

		return substr(self::$lipsum, 0, $length);
	}

}
