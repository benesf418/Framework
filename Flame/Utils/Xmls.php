<?php
/**
 * Class Xmls
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 10.07.13
 */
namespace Flame\Utils;

use Nette\StaticClassException;
use Nette\SmartObject;

class Xmls
{

	use Nette\SmartObject;

	/**
	 * @throws StaticClassException
	 */
	public function __construct()
	{
		throw new StaticClassException;
	}

	/**
	 * @param $object
	 * @return mixed
	 */
	public static function toArray($object)
	{
		return json_decode(json_encode((array) $object), 1);
	}
}