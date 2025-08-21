<?php

namespace Elgg;

class InvokerIntegrationTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		self::createApplication(['isolate' => true]);
	}
	
	public function testSystemLogIntegrationForceDisable() {
		$plugin = \ElggPlugin::fromId('system_log');
		
		if (!$plugin->isActive()) {
			$plugin->activate();
		}
		
		if (!$plugin->isActive()) {
			$this->markTestSkipped();
		}
		
		$invoker = _elgg_services()->invoker;
		$service = \Elgg\SystemLog\SystemLog::instance();
		
		$this->assertTrue($service->isLoggingEnabled());
		
		$invoker->call(ELGG_DISABLE_SYSTEM_LOG, function() use ($invoker, $service) {
			$this->assertFalse($service->isLoggingEnabled());
			
			$invoker->call(ELGG_ENABLE_SYSTEM_LOG, function() use ($service) {
				$this->assertTrue($service->isLoggingEnabled());
			});
			
			$this->assertFalse($service->isLoggingEnabled());
		});
		
		$this->assertTrue($service->isLoggingEnabled());
	}
	
	public function testSystemLogIntegrationForceEnable() {
		$plugin = \ElggPlugin::fromId('system_log');
		
		if (!$plugin->isActive()) {
			$plugin->activate();
		}
		
		if (!$plugin->isActive()) {
			$this->markTestSkipped();
		}
		
		$invoker = _elgg_services()->invoker;
		$service = \Elgg\SystemLog\SystemLog::instance();
		
		$service->disableLogging();
		$this->assertFalse($service->isLoggingEnabled());
		
		$invoker->call(ELGG_ENABLE_SYSTEM_LOG, function() use ($invoker, $service) {
			$this->assertTrue($service->isLoggingEnabled());
			
			$invoker->call(ELGG_DISABLE_SYSTEM_LOG, function() use ($service) {
				$this->assertFalse($service->isLoggingEnabled());
			});
			
			$this->assertTrue($service->isLoggingEnabled());
		});
		
		$this->assertFalse($service->isLoggingEnabled());
	}
}
