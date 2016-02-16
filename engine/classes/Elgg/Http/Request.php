<?php
namespace Elgg\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Elgg\Application;

/**
 * Elgg HTTP request.
 *
 * @access private
 */
class Request extends SymfonyRequest {

	/**
	 * Get the Elgg URL segments
	 *
	 * @return string[]
	 */
	public function getUrlSegments() {
		$path = trim($this->query->get(Application::GET_PATH_KEY), '/');
		if (!$path) {
			return array();
		}

		return explode('/', $path);
	}

	/**
	 * Get a cloned request with new Elgg URL segments
	 *
	 * @param string[] $segments URL segments
	 *
	 * @return Request
	 */
	public function setUrlSegments(array $segments) {
		$query = $this->query->all();
		$query[Application::GET_PATH_KEY] = '/' . implode('/', $segments);
		return $this->duplicate($query);
	}

	/**
	 * Get first Elgg URL segment
	 *
	 * @see \Elgg\Http\Request::getUrlSegments()
	 *
	 * @return string
	 */
	public function getFirstUrlSegment() {
		$segments = $this->getUrlSegments();
		if ($segments) {
			return array_shift($segments);
		} else {
			return '';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientIp() {
		$ip = parent::getClientIp();

		if ($ip == $this->server->get('REMOTE_ADDR')) {
			// try one more
			$ip_addresses = $this->server->get('HTTP_X_REAL_IP');
			if ($ip_addresses) {
				return array_pop(explode(',', $ip_addresses));
			}
		}

		return $ip;
	}
}
