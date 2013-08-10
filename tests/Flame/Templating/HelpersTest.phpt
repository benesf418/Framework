<?php
/**
 * Test: Flame\Tests\Templating\HelpersTest
 *
 * @testCase Flame\Tests\Templating\HelpersTest
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\Tests\Templating
 */
namespace Flame\Tests\Templating;

use Tester\Assert;
use Flame\Templating\Helpers;

require_once __DIR__ . '/../bootstrap.php';

class HelpersTest extends \Flame\Tester\TestCase
{

	public function testLoader()
	{
		$method = 'urlencode';
		$expected = array('Flame\Templating\Helpers', $method);
		Assert::equal($expected, Helpers::staticLoader($method));

		$method = 'missingMethod';
		Assert::null(Helpers::staticLoader($method));
	}

	public function testUrldecode()
	{
		$string = "swedish+house+mafia+don%27t+you+worry+child";
		Assert::equal(urldecode($string), Helpers::urldecode($string));
	}

	public function testUrlencode()
	{
		$string = "swedish house mafia don't you worry child";
		Assert::equal(urlencode($string), Helpers::urlencode($string));
	}

	public function testDump()
	{
		$var = \Nette\ArrayHash::from(array('data' => array('1', 3)));
		Assert::equal($this->getExpectedDumpVar($var), $this->getActualDumpVar($var));
	}

	/**
	 * @param $var
	 * @return string
	 */
	protected function getExpectedDumpVar($var)
	{
		ob_start();
		\Nette\Diagnostics\Debugger::dump($var);

		return \Nette\Utils\Strings::normalize(ob_get_clean());
	}

	/**
	 * @param $var
	 * @return string
	 */
	protected function getActualDumpVar($var)
	{
		ob_start();
		Helpers::dump($var);

		return \Nette\Utils\Strings::normalize(ob_get_clean());
	}

	protected function testBr()
	{
		$s = 'Hello
		world!
		How are you today?';

		Assert::equal(nl2br($s), Helpers::br($s));
	}
}

id(new HelpersTest())->run();