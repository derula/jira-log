<?php

class TaskHtml {

	/**
	 * @var Task
	 */
	private $task;

	/**
	 * @var string
	 */
	private $comment = '';

	/**
	 * @var string
	 */
	private $time = '';


	public function __construct(Task $task) {
		$this->task = $task;
	}

	public function setTime($time) {
		$this->time = (string)$time;
		return $this;
	}

	public function setComment($comment) {
		$this->comment = (string)$comment;
		return $this;
	}

	public function __toString() {
		$time = $this->time;
		$time = preg_replace('~([^0-9,])~', '', $time);
		$time = explode(',', $time);
		if (!isset($time[1])) {
			$time[1] = 0;
		}

		$t = new Template();
		$t->assignByArray(array(
			'issue' => $this->task->getIssue(),
			'summary' => $this->task->getSummary(),
			'timeHour' => $time[0],
			'timeMin' => ($time[1]/100)*60,
			'comment' => $this->comment,
		));

		return $t->fetch('taskHtml.tpl');
	}
}