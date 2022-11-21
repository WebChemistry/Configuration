<?php declare(strict_types = 1);

namespace WebChemistry\Configuration\Handler;

use Nette\Http\FileUpload;

final class LocalFileHandler implements FileHandler
{

	public function __construct(
		private string $baseDir,
		private string $baseUrl,
	)
	{
		$this->baseDir = rtrim($this->baseDir, '/');
		$this->baseUrl = rtrim($this->baseUrl, '/');
	}

	public function upload(FileUpload $upload, string $path): string
	{
		$upload->move($this->baseDir . '/' . $path = ltrim($path, '/'));

		return $path;
	}

	public function getLink(string $path): string
	{
		return $this->baseUrl . '/' . ltrim($path, '/');
	}

}
