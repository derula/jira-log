<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASR_DIR', dirname(dirname(__FILE__)) . DS);

// quick and dirty autoloader
spl_autoload_register(function ($class) {
	$libFilePath =  BASR_DIR . 'lib/' . $class . '.php';

	if (file_exists($libFilePath)) {
		require_once $libFilePath;
	}
});

$user = isset($_POST['username']) ? $_POST['username'] : null;
$pass = isset($_POST['password']) ? $_POST['password'] : null;

Config::set(Config::KEY_BASE_DIR, BASR_DIR);
Config::set(Config::KEY_USERNAME, $user);
Config::set(Config::KEY_PASSWORD, $pass);
Config::set(Config::KEY_JIRA_HOST, file_get_contents(dirname(dirname(__FILE__)).DS.'host'));

$jira = new Jira();
$template = new Template();

$template->assignByArray(array(
	'host' => Config::get(Config::KEY_JIRA_HOST)
));

$html = '';

$requestUri = $_SERVER['REQUEST_URI'];


if (!empty($_SERVER['HTTP_X_IS_AJAX_CALL']) && $_SERVER['HTTP_X_IS_AJAX_CALL'] === 'yes') {
	$json = array();
	try {
		switch ($requestUri) {
			case '/test':
				$json = $jira->testConnection();
				break;
		}
	}
	catch(Exception $e) {
		$json = array('error' => $e->getMessage());
	}

	$html = json_encode($json);
}
else {
	$html = $template->fetch('index.tpl');
	$template->assign('content', $html);
	$html = $template->fetch('body.tpl');
}

echo $html;
