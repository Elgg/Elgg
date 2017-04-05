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
	 * @param bool $raw If true, the segments will not be HTML escaped
	 *
	 * @return string[]
	 */
	public function getUrlSegments($raw = false) {
		$path = trim($this->getPathInfo(), '/');
		if (!$raw) {
			$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
		}
		if (!$path) {
			return [];
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
		$base_path = trim($this->getBasePath(), '/');
		$server = $this->server->all();
		$server['REQUEST_URI'] = "$base_path/" . implode('/', $segments);

		return $this->duplicate(null, null, null, null, null, $server);
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
				$ip_addresses = explode(',', $ip_addresses);
				return array_pop($ip_addresses);
			}
		}

		return $ip;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isXmlHttpRequest() {
		return (strtolower($this->headers->get('X-Requested-With')) === 'xmlhttprequest'
			|| $this->query->get('X-Requested-With') === 'XMLHttpRequest'
			|| $this->request->get('X-Requested-With') === 'XMLHttpRequest');
		// GET/POST check is necessary for jQuery.form and other iframe-based "ajax". #8735
	}
}
