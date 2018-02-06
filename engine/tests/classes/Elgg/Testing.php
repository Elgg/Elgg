<?php
/**
 *
 */

namespace Elgg;

use Elgg\Http\Request;
use Elgg\Project\Paths;
use ElggUser;
use RuntimeException;

/**
 * Testing trait that provides utility methods agnostic to testing framework
 * This trait can be shared e.g. between PHPUnit and Simpletest test cases
 */
trait Testing {

	/**
	 * @var ElggUser
	 */
	protected $_testing_admin;

	/**
	 * Resolve test file name in /test_files
	 *
	 * @param string $filename File name
	 *
	 * @return string
	 */
	public function normalizeTestFilePath($filename = '') {
		$filename = ltrim($filename, '/');
		$append_slash = substr($filename, -1, 1) === '/';
		return Paths::sanitize(Paths::elgg() . "/engine/tests/test_files/$filename", $append_slash);
	}

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
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
		$site_url = elgg_get_site_url();
		$path = '/' . ltrim(substr(elgg_normalize_url($uri), strlen($site_url)), '/');

		if ($add_csrf_tokens) {
			$ts = time();
			$parameters['__elgg_ts'] = $ts;
			$parameters['__elgg_token'] = _elgg_services()->csrf->generateActionToken($ts);
		}

		$request = Request::create($path, $method, $parameters);

		$cookie_name = _elgg_config()->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);

		$request->headers->set('Referer', elgg_normalize_url('phpunit'));

		if ($ajax) {
			$request->headers->set('X-Requested-With', 'XMLHttpRequest');
			if ($ajax >= 2) {
				$request->headers->set('X-Elgg-Ajax-API', (string) $ajax);
			}
		}

		return $request;
	}

	/**
	 * Returns an admin user
	 * @return ElggUser
	 * @throws RuntimeException
	 */
	public function getAdmin() {

		$admin = $this->_testing_admin;
		if (!$admin) {
			$admins = elgg_get_admins([
				'limit' => 1,
				'order_by' => 'e.time_created ASC',
			]);

			$admin = false;
			if ($admins) {
				$admin = array_shift($admins);
			}

			if (!$admin) {
				$admin = $this->createUser([
					'admin' => 'yes',
				]);
			}

			$this->_testing_admin = $admin;
		}

		if (!$admin instanceof ElggUser || !$admin->isAdmin()) {
			throw new RuntimeException("Unable to load an administrator user entity to perform tests.");
		}

		return $admin;
	}
}
