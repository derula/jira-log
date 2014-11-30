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

		// possibility to implement other parser
		switch (true) {
			default:
				$parser = new ParserCompact($sheet, $alternateIssue);
		}

		return $parser->getTaskHtmlObjects();
	}
}
