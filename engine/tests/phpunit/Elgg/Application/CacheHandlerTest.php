<?php
namespace Elgg\Application;

class CacheHandlerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var CacheHandler
	 */
	protected $handler;

	public function setUp() {
		$this->handler = new CacheHandler(_elgg_testing_application());
	}

	protected function _testParseFail($input) {
		$this->assertEquals(array(), $this->handler->parsePath($input));
	}

	public function testCanParseValidRequest() {
		$this->assertEquals(array(
			'ts' => '1234',
			'viewtype' => 'default',
			'view' => 'hel/8lo-wo_rl.d.js',
		), $this->handler->parsePath('/cache/1234/default/hel/8lo-wo_rl.d.js'));
	}

	public function testCantParseDoubleDot() {
		$this->_testParseFail('/cache/1234/default/hel/8lo-wo_rl..d.js');
	}

	public function testParserRequiresNumericTs() {
		$this->_testParseFail('/cache/a1234/default/hel/8lo-wo_rl.d.js');
	}

	public function testParserRequiresNonEmptyViewtype() {
		$this->_testParseFail('/cache/1234//hel/8lo-wo_rl.d.js');
	}

	public function testParserHasCharWhitelist() {
		$this->_testParseFail('/cache/a1234/default/he~l/8lo-wo_rl.d.js');
	}

	public function testParseRequiresLeadingSlash() {
		$this->_testParseFail('cache/1234/default/hello/world');
	}
}

