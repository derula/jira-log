<?php

/**
 * Class Auth
 *
 * @author Manuel Will <insphare@gmail.com>
 */
class Auth {

	/**
	 * @var null|string
	 */
	private $user = null;

	/**
	 * @var null|string
	 */
	private $pass = null;

	/**
	 * @param string $user
	 * @param string $pass
	 */
	public function __construct($user, $pass) {
		$this->user = (string)$user;
		$this->pass = (string)$pass;
	}

	/**
	 * @author Manuel Will <insphare@gmail.com>
	 * @return null|string
	 */
	public function getPass() {
		return $this->pass;
	}

	/**
	 * @author Manuel Will <insphare@gmail.com>
	 * @return null|string
	 */
	public function getUser() {
		return $this->user;
	}
}
