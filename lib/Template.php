<?php

/**
 * Class Template
 */
class Template {

	/**
	 * @var array
	 */
	private $variables = array();

	/**
	 * @var string
	 */
	private $viewDirectory = '';

	/**
	 *
	 */
	public function __construct() {
		$this->viewDirectory = Config::get(Config::KEY_BASE_DIR) . 'view' . DS ;
	}

	/**
	 * @param $variableName
	 * @param $value
	 * @return $this
	 */
	public function assign($variableName, $value) {
		$this->variables[(string)$variableName] = $value;
		return $this;
	}

	/**
	 * @param array $data
	 */
	public function assignByArray(array $data) {
		foreach ($data as $variableName => $value) {
			$this->assign($variableName, $value);
		}
	}

	/**
	 * @param $variableName
	 * @return string
	 */
	private function getVariable($variableName) {
		$keys = explode('.', $variableName);
		$firstKey = current($keys);
		unset($keys[0]);

		switch ($firstKey) {
			case 'session':
				$mixed = $_SESSION;
				break;

			default:
				$mixed = (isset($this->variables[(string)$firstKey])) ? $this->variables[(string)$firstKey] : '';
		}

		return $this->resolveVariable($mixed, $keys);
	}

	/**
	 * @param mixed $mixedData
	 * @param array $deepPath
	 * @return string
	 */
	private function resolveVariable($mixedData, array $deepPath) {
		foreach ($deepPath as $key) {
			if (isset($mixedData[$key])) {
				$mixedData = $mixedData[$key];
			}
			else {
				return '';
			}
		}

		return $mixedData;
	}

	/**
	 * @param $file
	 * @return mixed|string
	 */
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
