<?php

class Elgg_RouterTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		$this->hooks = new Elgg_PluginHooksService();
		$this->router = new Elgg_Router($this->hooks);
		$this->pages = dirname(dirname(__FILE__)) . '/test_files/pages';
	}

	function hello_page_handler($segments, $identifier) {
		include "{$this->pages}/hello.php";
		
		return true;
	}

	function testCanRegisterFunctionsAsPageHandlers() {
		$registered = $this->router->registerPageHandler('hello', array($this, 'hello_page_handler'));
		
		$this->assertTrue($registered);
		
		$request = Elgg_Http_Request::create('http://localhost/hello/');
		
		ob_start();
		$handled = $this->router->route($request);
		$output = ob_get_clean();
		
		$this->assertTrue($handled);
		$this->assertEquals("Hello, World!", $output);
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
}