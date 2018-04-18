<?php

namespace Elgg;

/**
 * Generic HTTP exception
 */
class HttpException extends \Exception {

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * Set params to provide context about the exception
	 *
	 * @param array $params Payload
	 *
	 * @return void
	 */
	public function setParams(array $params = []) {
		$this->params = $params;
	}

	/**
	 * Retrieve exception parameters
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Get a parameter value
	 * @param string $name Parameter name
	 * @return mixed|null
	 */
	public function getParam($name) {
		return elgg_extract($name, $this->params);
	}
	
	/**
	 * Set preferred redirect URL
	 * If set, a redirect response will be issued
	 *
	 * @param string $url URL
	 * @return void
	 */
	public function setRedirectUrl($url) {
		$this->url = $url;
	}

	/**
	 * Get preferred redirect URL
	 * @return string
	 */
	public function getRedirectUrl() {
		return $this->url;
	}
}
