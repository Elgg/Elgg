<?php
namespace Elgg;

class EntityPreloaderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	public $mock;

	/**
	 * @var EntityPreloader
	 */
	public $obj;

	public function setup() {
		$this->obj = new EntityPreloader();
		$dependency = new PreloaderMock_20140623();
		$this->obj->_callable_cache_checker = array($dependency, 'isCached');
		$this->obj->_callable_entity_loader = array($dependency, 'load');

		$this->mock = $this->getMock('Elgg\\PreloaderMock_20140623');
	}

	public function testAcceptsOnlyArraysOfObjects() {
		$inputs = array(
			'foo',
			array('0', array()),
			array(
				(object)array('foo' => 123),
				array('bar' => 234),
			),
		);
		$this->obj->_callable_cache_checker = array($this->mock, 'isCached');
		$this->mock->expects($this->once())->method('isCached')->with(123);
		foreach ($inputs as $input) {
			$this->obj->preload($input, array('foo', 'bar'));
		}
	}

	public function testOnlyLoadsIfNotCached() {
		$this->obj->_callable_entity_loader = array($this->mock, 'load');
		$this->mock->expects($this->once())->method('load')->with(array('guids' => array(234, 345)));
		$this->obj->preload(array(
			(object)array('foo' => 23,),
			(object)array('bar' => 234,),
			(object)array('bar' => 345,),
		), array('foo', 'bar'));
	}

	public function testOnlyLoadsIfMoreThanOne() {
		$this->obj->_callable_entity_loader = array($this->mock, 'load');
		$this->mock->expects($this->never())->method('load');
		$this->obj->preload(array(
			(object)array('foo' => 23,),
			(object)array('bar' => 234,),
		), array('foo', 'bar'));
	}

	public function testQuietlyIgnoresMissingProperty() {
		$this->obj->_callable_entity_loader = array($this->mock, 'load');
		$this->mock->expects($this->once())->method('load')->with(array('guids' => array(234, 345)));
		$this->obj->preload(array(
			(object)array('foo' => 234),
			(object)array(),
			(object)array('bar' => 345)
		), array('foo', 'bar'));
	}
}

class PreloaderMock_20140623 {
	function isCached($guid) {
		return $guid < 100;
	}
	function load($opts) {}
}
