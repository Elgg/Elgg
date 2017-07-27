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

	const REWRITE_TEST_TOKEN = '__testing_rewrite';
	const REWRITE_TEST_OUTPUT = 'success';

	/**
	 * Get the Elgg URL segments
	 *
	 * @param bool $raw If true, the segments will not be HTML escaped
	 *
	 * @return string[]
	 */
	public function getUrlSegments($raw = false) {
		$path = trim($this->getElggPath(), '/');
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
	 * Get the Request URI minus querystring
	 *
	 * @return string
	 */
	public function getElggPath() {
		if (php_sapi_name() === 'cli-server') {
			$path = $this->getRequestUri();
		} else {
			$path = $this->getPathInfo();
		}
		return preg_replace('~(\?.*)$~', '', $path);
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

	/**
	 * Sniff the Elgg site URL with trailing slash
	 *
	 * @return string
	 */
	public function sniffElggUrl() {
		$base_url = $this->getBaseUrl();

		// baseURL may end with the PHP script
		if ('.php' === substr($base_url, -4)) {
			$base_url = dirname($base_url);
		}

		return rtrim($this->getSchemeAndHttpHost() . $base_url, '/') . '/';
	}

	/**
	 * Is the request for checking URL rewriting?
	 *
	 * @return bool
	 */
	public function isRewriteCheck() {
		if ($this->getPathInfo() !== ('/' . self::REWRITE_TEST_TOKEN)) {
			return false;
		}

		if (!$this->get(self::REWRITE_TEST_TOKEN)) {
			return false;
		}

		return true;
	}

	/**
	 * Is PHP running the CLI server front controller
	 *
	 * @return bool
	 */
	public function isCliServer() {
		return php_sapi_name() === 'cli-server';
	}

	/**
	 * Is the request pointing to a file that the CLI server can handle?
	 *
	 * @param string $root Root directory
	 *
	 * @return bool
	 */
	public function isCliServable($root) {
		$file = rtrim($root, '\\/') . $this->getElggPath();
		if (!is_file($file)) {
			return false;
		}

		// http://php.net/manual/en/features.commandline.webserver.php
		$extensions = ".3gp, .apk, .avi, .bmp, .css, .csv, .doc, .docx, .flac, .gif, .gz, .gzip, .htm, .html, .ics, .jpe, .jpeg, .jpg, .js, .kml, .kmz, .m4a, .mov, .mp3, .mp4, .mpeg, .mpg, .odp, .ods, .odt, .oga, .ogg, .ogv, .pdf, .pdf, .png, .pps, .pptx, .qt, .svg, .swf, .tar, .text, .tif, .txt, .wav, .webm, .wmv, .xls, .xlsx, .xml, .xsl, .xsd, and .zip";

		// The CLI server routes ALL requests here (even existing files), so we have to check for these.
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if (!$ext) {
			return false;
		}

		$ext = preg_quote($ext, '~');
		return (bool) preg_match("~\\.{$ext}[,$]~", $extensions);
	}
}
