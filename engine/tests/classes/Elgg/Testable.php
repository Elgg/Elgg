<?php
/**
 *
 */

namespace Elgg;

/**
 * Defines method shared between various test suites
 */
interface Testable {

	/**
	 * Resolve test file name in /test_files
	 *
	 * @param string $filename File name
	 *
	 * @return string
	 */
	public function normalizeTestFilePath($filename = '');

	/**
	 * Create an HTTP request
	 *
	 * @param string $uri             URI of the request
	 * @param string $method          HTTP method
	 * @param array  $parameters      Query/Post parameters
	 * @param int    $ajax            AJAX api version (0 for non-ajax)
	 * @param bool   $add_csrf_tokens Add CSRF tokens
	 *
	 * @return Request
	 */
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false);

}