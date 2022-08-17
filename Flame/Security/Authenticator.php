<?php

namespace Flame\Security;

use Nette\SmartObject;
use Nette\Security\IAuthenticator;

/**
 * Users authenticator.
 */
abstract class Authenticator implements IAuthenticator
{

	use SmartObject;

	/**
	 * @param $password
	 * @param $salt
	 * @return string
	 */
	public function calculateHash($password, $salt = null)
	{
		if ($salt === null) {
			$salt = '$2a$07$' . md5(uniqid(time())) . '$';
		}

		return crypt($password, $salt);
	}

}
