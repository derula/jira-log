<?php

/**
 * Class ParserCompact
 */
class ParserCompact extends ParserAbstract {

	/**
	 * parse the sheet
	 */
	protected function parse() {
		foreach (explode(PHP_EOL, $this->getSheet()) as $stringTaskLine) {
			$stringTaskLine = trim($stringTaskLine, '|');
			$data = explode('|', $stringTaskLine);
			foreach ($data as &$row) {
				$row = trim($row);
			}

			list($task, $time, $comment) = $data;

			$comment = trim($comment);
			if ($comment === '-') {
				$comment = '';
			}

			$this->addTask($task, $time, $comment);
		}
	}

	/**
	 * Overwrite method. Called in ParserAbstract::addTask
	 *
	 * @param string $comment
	 */
	protected function formatComment(&$comment) {
		$mapping = array(
			'tl' => '',
			'ae' => 'Erfassung der Arbeitszeiten',
		);

		$comment = trim($comment);
		$parts = explode(',', $comment);
		foreach ($parts as $key => &$piece) {
			$piece = trim($piece);

			if (isset($mapping[$piece])) {
				$piece = $mapping[$piece];
			}

			if (empty($piece)) {
				unset($parts[$key]);
				continue;
			}

			$piece = '- ' . $piece;
		}

		$comment = implode(PHP_EOL, $parts);
	}
}
