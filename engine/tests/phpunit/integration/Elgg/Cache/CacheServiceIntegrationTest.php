<?php

namespace Elgg\Cache;

class CacheServiceIntegrationTest extends \Elgg\IntegrationTestCase {
	
	/**
	 * @dataProvider cacheServiceProvider
	 */
	public function testServiceEnabledState($service_name) {
		/**
		 * @var $service CacheService
		 */
		$service = _elgg_services()->$service_name;
		
		$this->assertIsBool($service->isEnabled());
		
		$service->enable();
		$this->assertTrue($service->isEnabled());
		
		$service->disable();
		$this->assertFalse($service->isEnabled());
	}
	
	/**
	 * @dataProvider cacheServiceProvider
	 */
	public function testItemStorage($service_name) {
		/**
		 * @var $service CacheService
		 */
		$service = _elgg_services()->$service_name;
		
		$service->enable();
		$service->clear();
		
		$this->assertNull($service->load('foo'));
		$service->delete('foo');
		
		$this->assertTrue($service->save('foo', 'bar'));
		$this->assertTrue($service->save('foo2', 'bar2'));
		$this->assertEquals('bar', $service->load('foo'));
		$this->assertEquals('bar2', $service->load('foo2'));
		
		$service->disable();
		$this->assertNull($service->load('foo'));
		$this->assertFalse($service->save('foo', 'bar2'));
		
		$service->enable();
		$this->assertEquals('bar', $service->load('foo'));
		
		$service->delete('foo');
		$this->assertNull($service->load('foo'));
		$this->assertEquals('bar2', $service->load('foo2'));
	}
	
	/**
	 * @dataProvider cacheServiceProvider
	 */
	public function testCacheResets($service_name) {
		/**
		 * @var $service CacheService
		 */
		$service = _elgg_services()->$service_name;
		
		$service->enable();
		$service->clear();
		
		// clear
		$service->save('foo', 'bar');
		$this->assertEquals('bar', $service->load('foo'));
		
		$service->clear();
		
		$this->assertNull($service->load('foo'));
		
		// just calling purge and invalidate to check they are available as compositecaches do not support these behaviours
		$service->purge();
		$service->invalidate();
	}
	
	public static function cacheServiceProvider(): array {
		return [
			['accessCache'],
			['autoloadCache'],
			['bootCache'],
			['entityCache'],
			['metadataCache'],
			['pluginsCache'],
			['queryCache'],
			['serverCache'],
			['systemCache'],
		];
	}
}
