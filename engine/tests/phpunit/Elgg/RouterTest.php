<?php

class Elgg_RouterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Elgg_PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Elgg_Router
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $pages;

	/**
	 * @var int
	 */
	protected $fooHandlerCalls = 0;

	function setUp() {
		$this->hooks = new Elgg_PluginHooksService();
		$this->router = new Elgg_Router($this->hooks);
		$this->pages = dirname(dirname(__FILE__)) . '/test_files/pages';
		$this->fooHandlerCalls = 0;
	}

	function hello_page_handler($segments, $identifier) {
		include "{$this->pages}/hello.php";

		return true;
	}

	function testCanRegisterFunctionsAsPageHandlers() {
		$registered = $this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));

		$this->assertTrue($registered);

		$path = "hello/1/\xE2\x82\xAC"; // euro sign
		$qs = http_build_query(array('__elgg_uri' => $path));
		$request = Elgg_Http_Request::create("http://localhost/?$qs");

		ob_start();
		$handled = $this->router->route($request);
		$output = ob_get_clean();

		$this->assertTrue($handled);
		$this->assertEquals($path, $output);
	}

	function testCanUnregisterPageHandlers() {
		$this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));
		$this->router->unregisterPageHandler('hello');

		$request = Elgg_Http_Request::create('http://localhost/hello/');

		ob_start();
		$handled = $this->router->route($request);
		$output = ob_get_clean();

		// Normally we would expect the router to return false for this request,
		// but since it checks for headers_sent() and PHPUnit issues output before
		// this test runs, the headers have already been sent. It's enough to verify
		// that the output we buffered is empty.
		// $this->assertFalse($handled);
		$this->assertEmpty($output);
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingHandlerInHookResultForBackwardsCompatibility() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'bar', array($this, 'bar_route_handler'));

		$query = http_build_query(array('__elgg_uri' => 'bar/baz'));

		ob_start();
		$this->router->route(Elgg_Http_Request::create("http://localhost/?$query"));
		ob_end_clean();

		$this->assertEquals(1, $this->fooHandlerCalls);
	}

	function foo_page_handler() {
		$this->fooHandlerCalls++;
		return true;
	}

	function bar_route_handler($hook, $type, $value, $params) {
		$value['handler'] = 'foo';
		return $value;
	}
}
