<?php

/**
 * Class Task
 */
class Task {

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
		$jira = new Jira();
		$response = $jira->getIssue($this->issue);

		if (strtolower($response['key']) === strtolower($this->issue)) {
			$this->summary = $response['fields']['summary'];
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
