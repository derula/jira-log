<?php

/**
 * Class TaskParserFactory
 */
class TaskParserFactory {

	/**
	 * @param string $sheet
	 * @param string $alternateIssue
	 * @return TaskHtml[]
	 */
	public static function getTaskHtmlObjects($sheet, $alternateIssue) {

		foreach ((array)Config::get(Config::KEY_PARSERS) as $classname) {
			if (!class_exists($classname)) continue;
			$format = $classname::canParse($sheet);
			if ($format) {
				$parser = new $classname($sheet, $alternateIssue, $format);
				break;
			}
		}

		return $parser->getTaskHtmlObjects();
	}
}
