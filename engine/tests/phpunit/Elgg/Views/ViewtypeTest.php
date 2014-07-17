<?php
namespace Elgg\Views;

use PHPUnit_Framework_TestCase as TestCase;

class ViewtypeTest extends TestCase {
	public function testViewtypesCanFallBack() {
		$viewtype = Viewtype::create('foo');
		$fallback = Viewtype::create('bar');
		
		$viewtype->setFallback($fallback);
		
		$this->assertTrue($viewtype->hasFallback());
		$this->assertEquals($fallback, $viewtype->getFallback());
		$this->assertFalse($fallback->hasFallback());
	}
}
