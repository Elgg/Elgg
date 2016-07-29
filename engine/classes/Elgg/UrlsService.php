<?php
namespace Elgg;

use Elgg\Http\Request;
use UFCOE\Elgg\SiteUrl;
use UFCOE\Elgg\SitePath;

/**
 * Service for working with URLs
 */
class UrlsService {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $site_url;

	/**
	 * @var SitePath
	 */
	private $current_path;

	/**
	 * Constructor
	 *
	 * @param Request $request  HTTP request
	 * @param string  $site_url Site URL
	 *
	 * @access private
	 * @internal Use the API elgg()->urls
	 */
	public function __construct(Request $request, $site_url) {
		$this->site_url = $site_url;
		$this->setRequest($request);
	}

	/**
	 * Returns the current page's complete URL.
	 *
	 * It uses the configured site URL for the hostname rather than depending on
	 * what the server uses to populate $_SERVER.
	 *
	 * @return string
	 */
	public function getCurrentUrl() {
		$url = parse_url($this->site_url);

		$page = $url['scheme'] . "://" . $url['host'];

		if (isset($url['port']) && $url['port']) {
			$page .= ":" . $url['port'];
		}

		$page = trim($page, "/");

		$page .= $this->request->getRequestUri();

		return $page;
	}

	/**
	 * Get the path (without leading slash) of the current page within the site.
	 * Subdirectories within the site URL are not included.
	 * The path elements are HTML escaped.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->current_path->getPath();
	}

	/**
	 * Get the URL segments, after the route:rewrite hook. The elements are HTML escaped.
	 * Subdirectories within the site URL are not included.
	 *
	 * @return string[]
	 */
	public function getUrlSegments() {
		return $this->current_path->getUrlSegments();
	}

	/**
	 * Is the current URL within the given path?
	 *
	 * E.g. if the current path is "blog/view/123", this would return true for "blog",
	 * but false for "bl" or "blog/vi".
	 *
	 * @param string $path Path to compare. E.g. "blog/view"
	 *
	 * @return bool
	 */
	public function isWithinPath($path) {
		return $this->current_path->isWithinPath($path);
	}

	/**
	 * Analyze a URL as a path within the Elgg site. Returns false if not within Elgg.
	 *
	 * @param string $url          URL
	 * @param bool   $match_scheme Require the URL scheme to match (e.g. https != http)
	 *
	 * @return SitePath|false
	 */
	public function analyzePathWithinSite($url, $match_scheme = true) {
		$site = new SiteUrl($this->site_url);
		return $site->getSitePath($url, $match_scheme);
	}

	/**
	 * Is this a safe HTTP(S) URL to redirect to?
	 *
	 * @note Use elgg()->urls->normalizeUrl() first to convert a site path into a URL.
	 *
	 * @param string   $location        URL to validate
	 * @param bool     $require_in_site Require the path to be within the Elgg site?
	 * @param callable $host_validator  Validator for hostnames that don't match the Elgg site
	 *
	 * @return bool
	 */
	public function isSafeRedirect($location, $require_in_site = false, callable $host_validator = null) {
		$result = (new SiteUrl($this->site_url))->analyzeUrl($location);
		if (!$result) {
			return false;
		}

		if ($result['host'] !== $result['site_host']) {
			if (!$host_validator) {
				return false;
			}

			// validate host
			$host = parse_url($location, PHP_URL_HOST);
			if (!$host || !call_user_func($host_validator, $host)) {
				return false;
			}
		}

		if ($require_in_site) {
			return $result['path_within_site'];
		}

		return true;
	}

	/**
	 * Converts shorthand urls to absolute urls.
	 *
	 * If the url is already absolute or protocol-relative, no change is made.
	 *
	 * @example
	 * elgg_normalize_url('');                   // 'http://my.site.com/'
	 * elgg_normalize_url('dashboard');          // 'http://my.site.com/dashboard'
	 * elgg_normalize_url('http://google.com/'); // no change
	 * elgg_normalize_url('//google.com/');      // no change
	 *
	 * @param string $url The URL to normalize
	 *
	 * @return string The absolute url
	 */
	function normalizeUrl($url) {
		// see https://bugs.php.net/bug.php?id=51192
		// from the bookmarks save action.
		$php_5_2_13_and_below = version_compare(PHP_VERSION, '5.2.14', '<');
		$php_5_3_0_to_5_3_2 = version_compare(PHP_VERSION, '5.3.0', '>=') &&
			version_compare(PHP_VERSION, '5.3.3', '<');

		if ($php_5_2_13_and_below || $php_5_3_0_to_5_3_2) {
			$tmp_address = str_replace("-", "", $url);
			$validated = filter_var($tmp_address, FILTER_VALIDATE_URL);
		} else {
			$validated = filter_var($url, FILTER_VALIDATE_URL);
		}

		// work around for handling absoluate IRIs (RFC 3987) - see #4190
		if (!$validated && (strpos($url, 'http:') === 0) || (strpos($url, 'https:') === 0)) {
			$validated = true;
		}

		if ($validated) {
			// all normal URLs including mailto:
			return $url;

		} elseif (preg_match("#^(\#|\?|//)#i", $url)) {
			// '//example.com' (Shortcut for protocol.)
			// '?query=test', #target
			return $url;

		} elseif (stripos($url, 'javascript:') === 0 || stripos($url, 'mailto:') === 0) {
			// 'javascript:' and 'mailto:'
			// Not covered in FILTER_VALIDATE_URL
			return $url;

		} elseif (preg_match("#^[^/]*\.php(\?.*)?$#i", $url)) {
			// 'install.php', 'install.php?step=step'
			return $this->site_url . $url;

		} elseif (preg_match("#^[^/?]*\.#i", $url)) {
			// 'example.com', 'example.com/subpage'
			return "http://$url";

		} else {
			// 'page/handler', 'mod/plugin/file.php'

			// trim off any leading / because the site URL is stored
			// with a trailing /
			return $this->site_url . ltrim($url, '/');
		}
	}

	/**
	 * Set the request object (after route:rewrite hook)
	 *
	 * @param Request $request HTTP request
	 * @access private
	 * @internal
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
		$this->current_path = new SitePath(implode('/', $this->request->getUrlSegments()));
	}
}
