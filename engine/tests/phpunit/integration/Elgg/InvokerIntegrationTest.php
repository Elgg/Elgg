<?php

namespace Elgg;

class InvokerIntegrationTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		self::createApplication(['isolate' => true]);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		
	}
	
	public function testSystemLogIntegrationForceDisable() {
		$plugin = elgg_get_plugin_from_id('system_log');
		
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
		$plugin = elgg_get_plugin_from_id('system_log');
		
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
