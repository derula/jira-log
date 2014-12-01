<?php

/**
 * Class Config
 *
 * @author Manuel Will <insphare@gmail.com>
 */
class Config {

	/**
	 *
	 */
	const KEY_BASE_DIR = 'base-dir';
	const KEY_JIRA_HOST = 'jira-host';
	const KEY_USERNAME= 'jira-user';
	const KEY_PASSWORD = 'jira-pass';

	/**
	 * Prevent new instance
	 */
	private function __construct() {}

	/**
	 * @var array
	 */
	private static $config = array();

	/**
	 * @author Manuel Will <insphare@gmail.com>
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value) {
		self::$config[(string)$key] = $value;
	}

	/**
	 * @author Manuel Will <insphare@gmail.com>
	 *
	 * @param string $key
	 *
	 * @return null
	 */
	public static function get($key) {
		if (isset(self::$config[(string)$key])) {
			return self::$config[(string)$key];
		}

		return null;
	}

}
