<?php

class Task {

	private $issue = '';
	private $summary = '';
	private $comment = '';

	public function __construct($issue, $comment) {
		$this->issue = (string)$issue;
		$this->comment = (string)$comment;
		$this->resolve();
	}

	private function resolve() {
		$jira = new Jira();
		$response = $jira->getIssue($this->issue);

		if (strtolower($response['key']) === strtolower($this->issue)) {
			$this->summary = $response['fields']['summary'];
		}
	}

	public function getSummary() {
		return $this->summary;
	}

	public function getIssue() {
		return $this->issue;
	}
}
