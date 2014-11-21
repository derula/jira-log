<?php

/**
 * Class Auth
 *
 * @author Manuel Will
 * @since 2014-11
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
	 * @param $user
	 * @param $pass
	 */
	public function __construct($user, $pass) {
		$this->user = (string)$user;
		$this->pass = (string)$pass;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return null|string
	 */
	public function getPass() {
		return $this->pass;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return null|string
	 */
	public function getUser() {
		return $this->user;
	}
}