<?php

/**
 * Class Task
 */
class Task {

	private static $summaryCache = [];

	/**
	 * @var string
	 */
	private $issue = '';

	/**
	 * @var string
	 */
	private $summary = '';

	/**
	 * @param $issue
	 */
	public function __construct($issue) {
		$this->issue = (string)$issue;
		$this->resolve();
	}

	/**
	 *
	 */
	private function resolve() {
		$key = strtolower($this->issue);
		if (isset(self::$summaryCache[$key])) {
			list($this->issue, $this->summary) = self::$summaryCache[$key];
			return;
		}
		
		$jira = new Jira();
		$response = $jira->getIssue($key);

		if (isset($response['key'])) {
			$this->issue = $response['key'];
			$this->summary = $response['fields']['summary'];
			self::$summaryCache[$key] =
			self::$summaryCache[$response['key']] =
				[$this->issue, $this->summary];
		}
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @return string
	 */
	public function getIssue() {
		return $this->issue;
	}

}
