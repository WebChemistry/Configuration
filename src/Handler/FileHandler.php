<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Handler;

use Nette\Http\FileUpload;

interface FileHandler
{

	public function upload(FileUpload $upload, string $path): string;

	public function getLink(string $path): string;

}
