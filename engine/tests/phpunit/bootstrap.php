<?php

/**
 * Instead of relying on this bootstrap, which leads to unreliable global state,
 * test cases should extend \Elgg\TestCase, which resets service providers
 * during test initialization making sure altered state does not
 * flow over to the next test case.
 * 
 * @deprecated 2.3
 */

if (!defined('PHPUNIT_ELGG_TESTING_APPLICATION') || function_exists('_elgg_testing_application')) {
	// this value is set by phpunit.xml
	return;
}

\Elgg\TestCase::bootstrap();

/**
 * Get/set an Application for testing purposes
 *
 * @param \Elgg\Application $app Elgg Application
 * @return \Elgg\Application
 * @deprecated 2.3 Use elgg() instead
 */
function _elgg_testing_application(\Elgg\Application $app = null) {
	if ($app) {
		\Elgg\Application::$_instance = $app;
	}
	return elgg();
}

/**
 * Set/get testing config
 *
 * @staticvar \Elgg\Config $inst
 * @param \Elgg\Config $config Config
 * @return \Elgg\Config
 * @depreated 2.3 Use _elgg_services() to access config
 */
function _elgg_testing_config(\Elgg\Config $config = null) {
	return \Elgg\TestCase::config($config);
}

/**
 * Create an HTTP request
 *
 * @param string $uri             URI of the request
 * @param string $method          HTTP method
 * @param array  $parameters      Query/Post parameters
 * @param int    $ajax            AJAX api version (0 for non-ajax)
 * @param bool   $add_csrf_tokens Add CSRF tokens
 * @return \Elgg\Http\Request
 * @deprecated 2.3
 */
function _elgg_testing_request($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
	return \Elgg\TestCase::prepareHttpRequest($uri, $method, $parameters, $ajax, $add_csrf_tokens);
}