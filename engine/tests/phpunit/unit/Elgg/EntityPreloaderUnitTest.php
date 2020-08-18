<?php

namespace Elgg;

use Elgg\Cache\EntityCache;
use Elgg\Helpers\PreloaderMock20140623;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group UnitTests
 */
class EntityPreloaderUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var MockObject
	 */
	public $mock;

	/**
	 * @var EntityPreloader
	 */
	public $obj;

	public function up() {
		$this->obj = new EntityPreloader(_elgg_services()->entityTable);
		$dependency = new PreloaderMock20140623();
		$this->obj->_callable_cache_checker = [$dependency, 'isCached'];
		$this->obj->_callable_entity_loader = [$dependency, 'load'];

		$this->mock = $this->createMock(PreloaderMock20140623::class);
	}

	public function down() {

	}

	public function testAcceptsOnlyArraysOfObjects() {
		$inputs = [
			'foo',
			['0', []],
			[
				(object) ['foo' => 123],
				['bar' => 234],
			],
		];
		$this->obj->_callable_cache_checker = [$this->mock, 'isCached'];
		$this->mock->expects($this->once())->method('isCached')->with(123);
		foreach ($inputs as $input) {
			$this->obj->preload($input, ['foo', 'bar']);
		}
	}

	public function testOnlyLoadsIfNotCached() {
		$this->obj->_callable_entity_loader = [$this->mock, 'load'];
		$this->mock->expects($this->once())->method('load')->with([
			'guids' => [234, 345],
			'limit' => EntityCache::MAX_SIZE,
			'order_by' => false,
		]);
		$this->obj->preload([
			(object) ['foo' => 23],
			(object) ['bar' => 234],
			(object) ['bar' => 345],
		], ['foo', 'bar']);
	}

	public function testOnlyLoadsIfMoreThanOne() {
		$this->obj->_callable_entity_loader = [$this->mock, 'load'];
		$this->mock->expects($this->never())->method('load');
		$this->obj->preload([
			(object) ['foo' => 23],
			(object) ['bar' => 234],
		], ['foo', 'bar']);
	}

	public function testQuietlyIgnoresMissingProperty() {
		$this->obj->_callable_entity_loader = [$this->mock, 'load'];
		$this->mock->expects($this->once())->method('load')->with([
			'guids' => [234, 345],
			'limit' => EntityCache::MAX_SIZE,
			'order_by' => false,
		]);
		$this->obj->preload([
			(object) ['foo' => 234],
			(object) [],
			(object) ['bar' => 345],
		], ['foo', 'bar']);
	}
}
