<?php

/**
 * Class Request
 *
 * @author Manuel Will
 * @since 2014-11
 */
class Request {

	/**
	 * Method variants
	 */
	const METHOD_GET = 1;
	const METHOD_POST = 2;

	/**
	 * @var int
	 */
	private $debug = 0;

	/**
	 * @var string
	 */
	private $host = '';

	/**
	 * @var string
	 */
	private $path = '';

	/**
	 * @var array
	 */
	private $params = array();

	/**
	 * @var int
	 */
	private $method = self::METHOD_GET;

	/** @var null Auth */
	private $auth = null;

	/**
	 * @param string $host
	 */
	public function __construct($host) {
		$this->host = (string)$host;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @param string $path
	 *
	 * @return $this
	 */
	public function setPath($path) {
		$this->path = $path;

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @param Auth $auth
	 *
	 * @return $this
	 */
	public function setAuth(Auth $auth) {
		$this->auth = $auth;

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return $this
	 */
	public function setMethodPost() {
		$this->method = self::METHOD_POST;

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 * @return $this
	 */
	public function setMethodGet() {
		$this->method = self::METHOD_GET;

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setParam($key, $value) {
		$this->params[(string)$key] = $value;

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setParams(array $params) {
		foreach ($params as $key => $value) {
			$this->setParam($key, $value);
		}

		return $this;
	}

	/**
	 * @author Manuel Will
	 * @since 2014-11
	 *
	 * @throws UnauthorizedException
	 * @throws RequestException
	 * @return mixed|array
	 */
	public function get() {
		$url = $this->host . $this->path;
		$curl = curl_init();

		if (!empty($this->params) && $this->method === self::METHOD_GET) {
			$url .= '?' . http_build_query($this->params);
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if ($this->auth instanceof Auth) {
			curl_setopt($curl, CURLOPT_USERPWD, sprintf("%s:%s", $this->auth->getUser(), $this->auth->getPass()));
		}

		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_VERBOSE, $this->debug);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));

		if (!empty($this->params)) {
			switch ($this->method) {
				case self::METHOD_POST:
					curl_setopt($curl, CURLOPT_POST, 1);
					break;
			}
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
		}

		$data = curl_exec($curl);
		$errorNumber = curl_errno($curl);

		if ($errorNumber > 0) {
			throw new RequestException(sprintf('Jira request failed: code = %s, "%s"', $errorNumber, curl_error($curl)));
		}
		// if empty result and status != "204 No Content"
		if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401) {
			throw new UnauthorizedException("Unauthorized");
		}

		if ($data === '' && curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
			throw new RequestException("JIRA Rest server returns unexpected result.");
		}

		if (is_null($data)) {
			throw new RequestException("JIRA Rest server returns unexpected result.");
		}

		return json_decode($data, true);
	}
}