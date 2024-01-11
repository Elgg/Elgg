<?php

namespace Elgg\Traits\Entity;

use Elgg\IntegrationTestCase;

abstract class PluginSettingsIntegrationTestCase extends IntegrationTestCase {
	
	/**
	 * @var \ElggUser
	 */
	protected $entity;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		parent::up();
		
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);
		
		$this->entity = $this->getEntity();
	}
	
	/**
	 * Get the testing entity
	 *
	 * @return \ElggEntity
	 */
	abstract protected function getEntity(): \ElggEntity;
	
	/**
	 * @dataProvider namespaceProvider
	 */
	public function testGetNamespacedPluginSettingName(string $plugin_id, string $setting_name) {
		$result = $this->entity->getNamespacedPluginSettingName($plugin_id, $setting_name);
		
		$this->assertIsString($result);
		$this->assertEquals("plugin:{$this->entity->getType()}_setting:{$plugin_id}:{$setting_name}", $result);
	}
	
	public static function namespaceProvider() {
		return [
			['test_plugin', 'foo'],
			['test_plugin', 'bar'],
			['static_config', 'foo'],
			['static_config', 'bar'],
		];
	}
	
	/**
	 * @dataProvider setPluginSettingProvider
	 */
	public function testSetGetRemovePluginSettings(string $plugin_id, string $setting_name, $setting_value) {
		$plugin_setting_name = $this->entity->getNamespacedPluginSettingName($plugin_id, $setting_name);
		
		$this->assertEmpty($this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals('default', $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEmpty($this->entity->getMetadata($plugin_setting_name));
		
		$this->assertTrue($this->entity->setPluginSetting($plugin_id, $setting_name, $setting_value));
		
		$this->assertEquals($setting_value, $this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals($setting_value, $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEquals($setting_value, $this->entity->getMetadata($plugin_setting_name));
		
		$this->assertTrue($this->entity->removePluginSetting($plugin_id, $setting_name));
		$this->assertEmpty($this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals('default', $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEmpty($this->entity->getMetadata($plugin_setting_name));
	}
	
	public static function setPluginSettingProvider() {
		return [
			['test_plugin', 'foo', 'bar'],
			['test_plugin', 'bar', 'foo'],
			['test_plugin', 'foo', 123],
			['test_plugin', 'foo', 1.23],
			['test_plugin', 'foo', false],
			['test_plugin', 'foo', true],
			['test_plugin', 'multiple', ['a', 'b']],
		];
	}
	
	/**
	 * @dataProvider invalidPluginSettingValueProvider
	 */
	public function testSetInvalidPluginSettingValue($value) {
		_elgg_services()->logger->disable();
		
		$this->assertFalse($this->entity->setPluginSetting('test_plugin', 'invalid_value', $value));
	}
	
	/**
	 * @dataProvider invalidPluginSettingValueProvider
	 */
	public function testUseEventToConvertInvalidPluginSettingValue($invalid_value) {
		$plugin_event = $this->registerTestingEvent('plugin_setting', $this->entity->getType(), function(\Elgg\Event $event) {
			return serialize($event->getValue());
		});
		
		$this->assertTrue($this->entity->setPluginSetting('test_plugin', 'foo', $invalid_value));
		$plugin_event->assertNumberOfCalls(1);
		$plugin_event->assertValueBefore($invalid_value);
		$plugin_event->assertValueAfter(serialize($invalid_value));
		
		$this->assertEquals(serialize($invalid_value), $this->entity->getPluginSetting('test_plugin', 'foo'));
	}
	
	public static function invalidPluginSettingValueProvider() {
		return [
			[new \stdClass()],
		];
	}
}
