<?php

class Task {

	private $issue = '';
	private $summary = '';
	private $comment = '';

	public function __construct($issue) {
		$this->issue = (string)$issue;
		$this->resolve();
	}

	private function resolve() {
		$jira = new Jira();
		$response = $jira->getIssue($this->issue);

		if (strtolower($response['key']) === strtolower($this->issue)) {

		}

		$response['fields']['summary'];
		$response['fields']['summary'];

		var_dump($response);exit;
	}

}
