<?php

namespace Elgg\Http;

/**
 * Create, sanitize and compare urls
 *
 * @since 4.3
 * @internal
 */
class Urls {

	/**
	 * Sets elements in a URL's query string.
	 *
	 * @param string $url      The URL
	 * @param array  $elements Key/value pairs to set in the URL. If the value is null, the
	 *                         element is removed from the URL.
	 *
	 * @return string The new URL with the query strings added
	 */
	public function addQueryElementsToUrl(string $url, array $elements): string {
		$url_array = parse_url($url);

		if (isset($url_array['query'])) {
			$query = elgg_parse_str($url_array['query']);
		} else {
			$query = [];
		}
	
		foreach ($elements as $k => $v) {
			if ($v === null) {
				unset($query[$k]);
			} else {
				$query[$k] = $v;
			}
		}
	
		// why check path? A: if no path, this may be a relative URL like "?foo=1". In this case,
		// the output "" would be interpreted the current URL, so in this case we *must* set
		// a query to make sure elements are removed.
		if ($query || empty($url_array['path'])) {
			$url_array['query'] = http_build_query($query);
		} else {
			unset($url_array['query']);
		}
		
		$string = $this->buildUrl($url_array, false);
	
		// Restore relative protocol to url if missing and is provided as part of the initial url (see #9874)
		if (!isset($url['scheme']) && (substr($url, 0, 2) == '//')) {
			$string = "//{$string}";
		}
		
		return $string;
	}

	/**
	 * Adds action tokens to URL
	 *
	 * Use this function to append action tokens to a URL's GET parameters.
	 * This will preserve any existing GET parameters.
	 *
	 * @param string $url         Full action URL
	 * @param bool   $html_encode HTML encode the url? (default: false)
	 *
	 * @return string URL with action tokens
	 */
	public function addActionTokensToUrl(string $url, bool $html_encode = false): string {
		$url = $this->normalizeUrl($url);
		$components = parse_url($url);
	
		if (isset($components['query'])) {
			$query = elgg_parse_str($components['query']);
		} else {
			$query = [];
		}
	
		if (isset($query['__elgg_ts'], $query['__elgg_token'])) {
			return $url;
		}
	
		// append action tokens to the existing query
		// CSRF service is not DI injected because Urls is used by installer and CSRF requires DB installed
		$query['__elgg_ts'] = _elgg_services()->csrf->getCurrentTime()->getTimestamp();
		$query['__elgg_token'] = _elgg_services()->csrf->generateActionToken($query['__elgg_ts']);
		$components['query'] = http_build_query($query);
	
		// rebuild the full url
		return $this->buildUrl($components, $html_encode);
	}
	
	/**
	 * Builds a URL from the a parts array like one returned by {@link parse_url()}.
	 *
	 * @note If only partial information is passed, a partial URL will be returned.
	 *
	 * @param array $parts       Associative array of URL components like parse_url() returns
	 *                           'user' and 'pass' parts are ignored because of security reasons
	 * @param bool  $html_encode HTML Encode the url?
	 *
	 * @see https://github.com/Elgg/Elgg/pull/8146#issuecomment-91544585
	 *
	 * @return string Full URL
	 */
	public function buildUrl(array $parts, bool $html_encode = true): string {
		// build only what's given to us
		$scheme = isset($parts['scheme']) ? "{$parts['scheme']}://" : '';
		$host = isset($parts['host']) ? "{$parts['host']}" : '';
		$port = isset($parts['port']) ? ":{$parts['port']}" : '';
		$path = isset($parts['path']) ? "{$parts['path']}" : '';
		$query = isset($parts['query']) ? "?{$parts['query']}" : '';
		$fragment = isset($parts['fragment']) ? "#{$parts['fragment']}" : '';
	
		$string = $scheme . $host . $port . $path . $query . $fragment;
	
		return $html_encode ? htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false) : $string;
	}
	
	/**
	 * Converts shorthand URLs to absolute URLs, unless the given URL is absolute, protocol-relative,
	 * or starts with a protocol/fragment/query
	 *
	 * @example
	 * elgg_normalize_url('');                   // 'http://my.site.com/'
	 * elgg_normalize_url('dashboard');          // 'http://my.site.com/dashboard'
	 * elgg_normalize_url('http://google.com/'); // no change
	 * elgg_normalize_url('//google.com/');      // no change
	 *
	 * @param string $url The URL to normalize
	 *
	 * @return string The absolute URL
	 */
	public function normalizeUrl(string $url): string {
		$url = str_replace(' ', '%20', $url);
	
		if ($this->isValidMultiByteUrl($url)) {
			// fix invalid scheme in site url
			$protocol_less_site_url = preg_replace('/^https?:/i', ':', elgg_get_site_url());
			$protocol_less_site_url = rtrim($protocol_less_site_url, '/');
			$protocol_less_site_url = str_replace('/', '\/', $protocol_less_site_url);
	
			return preg_replace("/^https?{$protocol_less_site_url}\/?/i", elgg_get_site_url(), $url);
		}
	
		$matches = [];
		if (preg_match('#^([a-z]+)\\:#', $url, $matches)) {
			// we don't let http/https: URLs fail filter_var(), but anything else starting with a protocol
			// is OK
			if ($matches[1] !== 'http' && $matches[1] !== 'https') {
				return $url;
			}
		}
	
		if (preg_match('#^(\\#|\\?|//)#', $url)) {
			// starts with '//' (protocol-relative link), query, or fragment
			return $url;
		}
	
		if (preg_match('#^[^/]*\\.php(\\?.*)?$#', $url)) {
			// root PHP scripts: 'install.php', 'install.php?step=step'. We don't want to confuse these
			// for domain names.
			return elgg_get_site_url() . $url;
		}
	
		if (preg_match('#^[^/?]*\\.#', $url)) {
			// URLs starting with domain: 'example.com', 'example.com/subpage'
			return "http://{$url}";
		}
	
		// 'page/handler', 'mod/plugin/file.php'
		// trim off any leading / because the site URL is stored
		// with a trailing /
		return elgg_get_site_url() . ltrim($url, '/');
	}
	
	/**
	 * Test if two URLs are functionally identical.
	 *
	 * @tip If $ignore_params is used, neither the name nor its value will be considered when comparing.
	 *
	 * @tip The order of GET params doesn't matter.
	 *
	 * @param string $url1          First URL
	 * @param string $url2          Second URL
	 * @param array  $ignore_params GET params to ignore in the comparison
	 *
	 * @return bool
	 */
	public function isUrlIdentical(string $url1, string $url2, array $ignore_params): bool {
		$url1 = $this->normalizeUrl($url1);
		$url2 = $this->normalizeUrl($url2);
	
		if ($url1 === $url2) {
			return true;
		}
	
		$url1_info = parse_url($url1);
		$url2_info = parse_url($url2);
	
		if (isset($url1_info['path'])) {
			$url1_info['path'] = trim($url1_info['path'], '/');
		}
		
		if (isset($url2_info['path'])) {
			$url2_info['path'] = trim($url2_info['path'], '/');
		}
	
		// compare basic bits
		$parts = ['scheme', 'host', 'path'];
	
		foreach ($parts as $part) {
			if (isset($url1_info[$part], $url2_info[$part]) && $url1_info[$part] !== $url2_info[$part]) {
				return false;
			} elseif (isset($url1_info[$part]) && !isset($url2_info[$part])) {
				return false;
			} elseif (!isset($url1_info[$part]) && isset($url2_info[$part])) {
				return false;
			}
		}
	
		// quick compare of get params
		if (isset($url1_info['query'], $url2_info['query']) && $url1_info['query'] === $url2_info['query']) {
			return true;
		}
	
		// compare get params that might be out of order
		$url1_params = [];
		$url2_params = [];
	
		if (isset($url1_info['query'])) {
			$url1_info['query'] = html_entity_decode($url1_info['query']);
			if (!elgg_is_empty($url1_info['query'])) {
				$url1_params = elgg_parse_str($url1_info['query']);
			}
		}
	
		if (isset($url2_info['query'])) {
			$url2_info['query'] = html_entity_decode($url2_info['query']);
			if (!elgg_is_empty($url2_info['query'])) {
				$url2_params = elgg_parse_str($url2_info['query']);
			}
		}
	
		// drop ignored params
		foreach ($ignore_params as $param) {
			unset($url1_params[$param]);
			unset($url2_params[$param]);
		}
	
		// array_diff_assoc only returns the items in arr1 that aren't in arrN
		// but not the items that ARE in arrN but NOT in arr1
		// if arr1 is an empty array, this function will return 0 no matter what.
		// since we only care if they're different and not how different,
		// add the results together to get a non-zero (ie, different) result
		$diff_count = count($this->arrayDiffAssocRecursive($url1_params, $url2_params));
		$diff_count += count($this->arrayDiffAssocRecursive($url2_params, $url1_params));
		if ($diff_count > 0) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * Use a "fixed" filter_var() with FILTER_VALIDATE_URL that handles multi-byte chars.
	 *
	 * This function is static because it is used in \ElggInstaller.
	 * During installation this service can't be constructed because the database is not yet available.
	 *
	 * @param string $url URL to validate
	 *
	 * @return bool
	 * @internal
	 */
	public static function isValidMultiByteUrl(string $url): bool {
		// based on http://php.net/manual/en/function.filter-var.php#104160
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;
		}
	
		// Check if it has unicode chars.
		$l = elgg_strlen($url);
		if (strlen($url) === $l) {
			return false;
		}
	
		// Replace wide chars by X
		$s = '';
		for ($i = 0; $i < $l; ++$i) {
			$ch = elgg_substr($url, $i, 1);
			$s .= (strlen($ch) > 1) ? 'X' : $ch;
		}
	
		// Re-check now.
		return (bool) filter_var($s, FILTER_VALIDATE_URL);
	}
	
	/**
	 * Computes the difference of arrays with additional index check
	 *
	 * @return array
	 *
	 * @see array_diff_assoc()
	 * @see https://github.com/Elgg/Elgg/issues/13016
	 */
	protected function arrayDiffAssocRecursive(): array {
		$args = func_get_args();
		$diff = [];
		
		foreach (array_shift($args) as $key => $val) {
			for ($i = 0, $j = 0, $tmp = [$val], $count = count($args); $i < $count; $i++) {
				if (is_array($val)) {
					if (!isset($args[$i][$key]) || !is_array($args[$i][$key]) || empty($args[$i][$key])) {
						$j++;
					} else {
						$tmp[] = $args[$i][$key];
					}
				} elseif (!array_key_exists($key, $args[$i]) || $args[$i][$key] !== $val) {
					$j++;
				}
			}
			
			if (is_array($val)) {
				$tmp = call_user_func_array([$this, 'arrayDiffAssocRecursive'], $tmp);
				if (!empty($tmp)) {
					$diff[$key] = $tmp;
				} elseif ($j == $count) {
					$diff[$key] = $val;
				}
			} elseif ($j == $count && $count) {
				$diff[$key] = $val;
			}
		}
		
		return $diff;
	}
}
