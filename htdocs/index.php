<?php

define('DS', DIRECTORY_SEPARATOR);

// quick and dirty autoloader
spl_autoload_register(function ($class) {
	$libFilePath = dirname(dirname(__FILE__)) . DS . 'lib/' . $class . '.php';

	if (file_exists($libFilePath)) {
		require_once $libFilePath;
	}
});

$user = isset($_POST['username']) ? $_POST['username'] : null;
$pass = isset($_POST['password']) ? $_POST['password'] : null;

Config::set(Config::KEY_USERNAME, $user);
Config::set(Config::KEY_PASSWORD, $pass);
Config::set(Config::KEY_JIRA_HOST, file_get_contents(dirname(dirname(__FILE__)).DS.'host'));

$jira = new Jira();
//@todo test+view+design

