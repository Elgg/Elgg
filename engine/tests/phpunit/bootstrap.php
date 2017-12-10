<?php

if (!date_default_timezone_get()) {
	date_default_timezone_set('America/Los_Angeles');
}

error_reporting(E_ALL | E_STRICT);

$app = \Elgg\IntegrationTestCase::createApplication();

/**
 * Get/set an Application for testing purposes
 *
 * @param \Elgg\Application $app Elgg Application
 *
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
 *
 * @return \Elgg\Config
 * @depreated 2.3 Use _elgg_services() to access config
 */
function _elgg_testing_config(\Elgg\Config $config = null) {
	if ($config) {
		\Elgg\Application::setGlobalConfig($config);
	}
	return \Elgg\Application::$_instance->_services->config;
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
 * @return \Elgg\Http\Request
 * @deprecated 2.3
 */
function _elgg_testing_request($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
	return \Elgg\UnitTestCase::prepareHttpRequest($uri, $method, $parameters, $ajax, $add_csrf_tokens);
}