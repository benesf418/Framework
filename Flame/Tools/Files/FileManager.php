<?php
/**
 * FileManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    13.12.12
 */

namespace Flame\Tools\Files;

use Nette\Utils\Strings;
use Flame\Utils\Files;

class FileManager extends \Nette\Object
{

	/** @var string */
	protected $baseDirPath;

	/** @var string */
	protected $filesDirPath;

	/**
	 * @param        $baseDirPath
	 * @param string $filesDirPath
	 */
	public function __construct($baseDirPath, $filesDirPath = '/media/images')
	{
		$this->baseDirPath = (string) $baseDirPath;
		$this->filesDirPath = (string) $filesDirPath;
	}

	/**
	 * @param $path
	 */
	public function setFilesDir($path)
	{
		$this->filesDirPath = (string)$path;
	}

	/**
	 * @return string
	 */
	public function getFilesDir()
	{
		return $this->filesDirPath;
	}

	/**
	 * @return string
	 */
	public function getBaseDir()
	{
		return $this->baseDirPath;
	}

	/**
	 * Save upladed file and return absolute path
	 *
	 * @param \Nette\Http\FileUpload $file
	 * @return string
	 * @throws \Nette\InvalidArgumentException
	 */
	public function saveFile(\Nette\Http\FileUpload $file)
	{

		if (!$file->isOk()) {
			throw new \Nette\InvalidArgumentException('File ' . $file->getName() . ' is not valid.');
		}

		FileSystem::mkDir($this->getAbsolutePath(), true, 0777, false);
		$name = Strings::webalize($this->removeFileType($file->getName())) . '.' . $this->getFileType($file->getName());
		$filePath = $this->getAbsolutePath() . DIRECTORY_SEPARATOR . $name;

		if (!file_exists($filePath)) {
			$file->move($filePath);
		} else {
			$new_name = Strings::random(5) . '_' . $name;
			$file->move(str_replace($name, $new_name, $filePath));
			$name = $new_name;
		}

		return $this->filesDirPath . DIRECTORY_SEPARATOR . $name;
	}

	/**
	 * Save file from url on server
	 *
	 * @param $url
	 * @param null $fileName
	 * @return bool|string
	 */
	public function downloadFile($url, $fileName = null)
	{
		if($fileName === null) {
			$fileName = $this->getFileName($url);
		}

		$fileDir = $this->getAbsolutePath() . DIRECTORY_SEPARATOR . $fileName;

		if ($file = FileSystem::read($url, false)) {
			if (FileSystem::write($fileDir, $file, true, 0777, false))
				return $this->filesDirPath . DIRECTORY_SEPARATOR . $fileName;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath()
	{
		return $this->baseDirPath . $this->filesDirPath;
	}

	/**
	 * Return ending of filename
	 *
	 * @param $name
	 * @return null
	 */
	public function getFileType($name)
	{
		return str_replace('.', '', strrchr($name, '.'));
	}

	/**
	 * Return name of file without ending
	 *
	 * @param $name
	 * @return mixed
	 */
	protected function removeFileType($name)
	{
		return str_replace('.' . $this->getFileType($name), '', $name);
	}

	/**
	 * Return name of file from URL or absolute path
	 *
	 * @param $path
	 * @return null
	 */
	protected function getFileName($path)
	{
		return Files::getFileName($path);
	}

}
