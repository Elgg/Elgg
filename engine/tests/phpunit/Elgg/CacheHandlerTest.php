<?php
namespace Elgg;


class CacheHandlerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Elgg\CacheHandler
	 */
	protected $handler;

	public function setUp() {
		$config = (object)array();
		$this->handler = new \Elgg\CacheHandler($config);
	}

	protected function _testParseFail($input) {
		$this->assertEquals(array(), $this->handler->parseRequestVar($input));
	}

	public function testCanParseValidRequest() {
		$this->assertEquals(array(
			'ts' => '1234',
			'viewtype' => 'default',
			'view' => 'hel/8lo-wo_rl.d.js',
		), $this->handler->parseRequestVar('1234/default/hel/8lo-wo_rl.d.js'));
	}

	public function testCantParseDoubleDot() {
		$this->_testParseFail('1234/default/hel/8lo-wo_rl..d.js');
	}

	public function testParserRequiresNumericTs() {
		$this->_testParseFail('a1234/default/hel/8lo-wo_rl.d.js');
	}

	public function testParserRequiresNonEmptyViewtype() {
		$this->_testParseFail('1234//hel/8lo-wo_rl.d.js');
	}

	public function testParserHasCharWhitelist() {
		$this->_testParseFail('a1234/default/he~l/8lo-wo_rl.d.js');
	}

	public function testParseSupportsLeadingSlash() {
		$this->assertEquals(array(
			'ts' => '1234',
			'viewtype' => 'default',
			'view' => 'hel/8lo-wo_rl.d.js',
		), $this->handler->parseRequestVar('/1234/default/hel/8lo-wo_rl.d.js'));
	}
}

