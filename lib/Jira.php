<?php

/**
 * Class Jira
 *
 * @author Manuel Will
 * @since 2014-11
 */
class Jira {

	/**
	 * @var Auth
	 */
	private $auth;

	/**
	 *
	 */
	public function __construct() {
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return Auth
	 */
	private function getAuth() {
		if (null === $this->auth) {
			$this->auth = new Auth(Config::get(Config::KEY_USERNAME), Config::get(Config::KEY_PASSWORD));
		}

		return $this->auth;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return Request
	 */
	private function getRequest() {
		$requestClass = new Request(Config::get(Config::KEY_JIRA_HOST));

		return $requestClass;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 */
	public function testConnection() {
		return $this->getRequest()->setMethodGet()->setPath('/rest/api/latest/serverInfo')->get();
	}

	public function getMyProfile() {
		$auth = $this->getAuth();
		$response = $this->getRequest()
			->setMethodGet()
			->setAuth($auth)
			->setPath('/rest/api/2/myself')
			->get()
		;
		return $response;
	}

	public function search($jql) {
		$auth = $this->getAuth();
		$params = array(
			'jql' => $jql,
		);

		$response = $this->getRequest()
			->setMethodPost()
			->setParams($params)
			->setAuth($auth)
			->setPath('/rest/api/2/search')
			->get()
		;
		return $response;
	}

	/**
	 * @return array|mixed
	 */
	public function getTimeTrackTask() {
		return $this->search('assignee = currentUser() and summary ~ "timetrack"');
	}

	/**
	 * @param $issue
	 * @return array|mixed
	 * @throws RequestException
	 * @throws UnauthorizedException
	 */
	public function getIssue($issue) {
		$auth = $this->getAuth();
		$response = $this->getRequest()
			->setMethodGet()
			->setAuth($auth)
			->setPath(sprintf('/rest/api/latest/issue/%s?expand=schema,names,transitions', $issue))
			->get()
		;

		return $response;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @param $issue
	 * @param $duration
	 * @param $comment
	 * @param null $strDateTime
	 *
	 * @return array|mixed
	 */
	public function logTime($issue, $duration, $comment, $strDateTime = null) {
		$auth = $this->getAuth();
		$response = $this->getIssue($issue);

		$internalIssueIdentifier = $response['id'];
		$date = new DateTime($strDateTime);

		$params = array(
			'comment' => $comment,
			'started' => $date->format('Y-m-d').'T'.$date->format('H:i:s').'.000+0000',
			'timeSpent' => $duration,
		);

		$response = $this->getRequest()
			->setMethodPost()
			->setParams($params)
			->setAuth($auth)
			->setPath(sprintf('/rest/api/latest/issue/%s/worklog?adjustEstimate=auto', $internalIssueIdentifier))
			->get()
		;

		return $response;
	}

}
