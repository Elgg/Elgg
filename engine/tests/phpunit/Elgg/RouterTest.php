<?php
namespace Elgg;


class RouterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Elgg\PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var \Elgg\Router
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
		$this->hooks = new \Elgg\PluginHooksService();
		$this->router = new \Elgg\Router($this->hooks);
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
		$request = \Elgg\Http\Request::create("http://localhost/?$qs");
		
		ob_start();
		$handled = $this->router->route($request);
		$output = ob_get_clean();
		
		$this->assertTrue($handled);
		$this->assertEquals($path, $output);

		$this->assertEquals(array(
			'hello' => array($this, 'hello_page_handler')
		), $this->router->getPageHandlers());
	}

	function testFailToRegisterInvalidCallback() {
		$registered = $this->router->registerPageHandler('hello', new \stdClass());

		$this->assertFalse($registered);
	}
	
	function testCanUnregisterPageHandlers() {
		$this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));
		$this->router->unregisterPageHandler('hello');
		
		$request = \Elgg\Http\Request::create('http://localhost/hello/');
		
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
		$this->router->route(\Elgg\Http\Request::create("http://localhost/?$query"));
		ob_end_clean();
		
		$this->assertEquals(1, $this->fooHandlerCalls);
	}

	/**
	 * 1. Register a page handler for `/foo`
	 * 2. Register a plugin hook that uses the "handler" result param
	 *    to route all `/bar/*` requests to the `/foo` handler.
	 * 3. Route a request for a `/bar` page.
	 * 4. Check that the `/foo` handler was called.
	 */
	function testRouteSupportsSettingIdentifierInHookResultForBackwardsCompatibility() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'bar', array($this, 'bar_route_identifier'));

		$query = http_build_query(array('__elgg_uri' => 'bar/baz'));

		ob_start();
		$this->router->route(\Elgg\Http\Request::create("http://localhost/?$query"));
		ob_end_clean();

		$this->assertEquals(1, $this->fooHandlerCalls);
	}

	function testRouteOverridenFromHook() {
		$this->router->registerPageHandler('foo', array($this, 'foo_page_handler'));
		$this->hooks->registerHandler('route', 'foo', array($this, 'bar_route_override'));

		$query = http_build_query(array('__elgg_uri' => 'foo'));

		ob_start();
		$this->router->route(\Elgg\Http\Request::create("http://localhost/?$query"));
		$result = ob_get_contents();
		ob_end_clean();

		$this->assertEquals("Page handler override from hook", $result);
		$this->assertEquals(0, $this->fooHandlerCalls);
	}
	
	function foo_page_handler() {
		$this->fooHandlerCalls++;
		return true;
	}
	
	function bar_route_handler($hook, $type, $value, $params) {
		$value['handler'] = 'foo';
		return $value;
	}

	function bar_route_identifier($hook, $type, $value, $params) {
		$value['identifier'] = 'foo';
		return $value;
	}

	function bar_route_override($hook, $type, $value, $params) {
		echo "Page handler override from hook";
		return false;
	}
}

