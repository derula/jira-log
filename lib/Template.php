<?php

class Template {

	/**
	 * @var array
	 */
	private $variables = array();

	/**
	 * @var string
	 */
	private $viewDirectory = '';

	public function __construct() {
		$this->viewDirectory = Config::get(Config::KEY_BASE_DIR) . 'view' . DS ;
	}

	public function assign($variableName, $value) {
		$this->variables[(string)$variableName] = $value;
	}

	public function assignByArray(array $data) {
		foreach ($data as $variableName => $value) {
			$this->assign($variableName, $value);
		}
	}

	private function getVariable($variableName) {
		if (isset($this->variables[(string)$variableName])) {
			return $this->variables[(string)$variableName];
		}

		return '';
	}

	public function fetch($file) {
		$path = $this->viewDirectory.$file;
		$content = '';
		if (file_exists($path)) {
			$content = file_get_contents($path);
			preg_match_all('~\{\$(?<var>[^}]+)\}~', $content, $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				$content = str_replace($match[0], $this->getVariable($match['var']), $content);
			}
		}

		return $content;
	}

}