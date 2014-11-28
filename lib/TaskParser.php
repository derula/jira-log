<?php

class TaskParser {

	private static $taskObjects = array();

	/**
	 * @var string
	 */
	private $sheet = '';

	/**
	 * @var string
	 */
	private $alternateIssue = '';

	/**
	 * @var TaskHtml[]
	 */
	private $tasks;


	public function __construct($sheet, $alternateIssue) {
		$this->sheet = (string)$sheet;
		$this->alternateIssue = (string)$alternateIssue;
	}

	public function getTaskHtmlObjects() {
		$this->parse();
		return $this->tasks;
	}

	private function getTaskObject($issue) {
		if (!isset(self::$taskObjects[$issue])) {
			self::$taskObjects[$issue] = new Task($issue);
		}

		return self::$taskObjects[$issue];
	}

	private function parse() {
		$sheet = &$this->sheet;
		$sheet = trim($sheet);

		foreach(explode(PHP_EOL, $sheet) as $stringTaskLine) {
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

			//@todo configurable
			if (!preg_match('~^VS-\d+~', $task)) {
				$comment = '- ' . $task . PHP_EOL . '- ' . $comment;
				$task = $this->alternateIssue;
			}

			$taskObject = $this->getTaskObject($task);
			$taskHtml = new TaskHtml($taskObject);
			$taskHtml->setComment($comment)->setTime($time);
			$this->tasks[] = $taskHtml;
		}
	}


}