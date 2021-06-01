<?php

namespace Elgg\Traits\Entity;

use Elgg\IntegrationTestCase;

abstract class ElggEntityPluginSettingsTestCase extends IntegrationTestCase {
	
	/**
	 * @var \ElggUser
	 */
	protected $entity;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);
		
		$this->entity = $this->getEntity();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->entity->delete();
		});
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
	
	public function namespaceProvider() {
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
		$private_setting_name = $this->entity->getNamespacedPluginSettingName($plugin_id, $setting_name);
		
		$this->assertEmpty($this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals('default', $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEmpty($this->entity->getPrivateSetting($private_setting_name));
		
		$this->assertTrue($this->entity->setPluginSetting($plugin_id, $setting_name, $setting_value));
		
		if (is_bool($setting_value)) {
			// booleans are cast to ints
			$setting_value = (int) $setting_value;
		}
		$this->assertEquals((string) $setting_value, $this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals((string) $setting_value, $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEquals((string) $setting_value, $this->entity->getPrivateSetting($private_setting_name));
		
		$this->assertTrue($this->entity->removePluginSetting($plugin_id, $setting_name));
		$this->assertEmpty($this->entity->getPluginSetting($plugin_id, $setting_name));
		$this->assertEquals('default', $this->entity->getPluginSetting($plugin_id, $setting_name, 'default'));
		$this->assertEmpty($this->entity->getPrivateSetting($private_setting_name));
	}
	
	public function setPluginSettingProvider() {
		return [
			['test_plugin', 'foo', 'bar'],
			['test_plugin', 'bar', 'foo'],
			['test_plugin', 'foo', 123],
			['test_plugin', 'foo', 1.23],
			['test_plugin', 'foo', false],
			['test_plugin', 'foo', true],
		];
	}
	
	/**
	 * @dataProvider invalidPluginSettingValueProvider
	 */
	public function testSetInvalidPluginSettingValue($value) {
		_elgg_services()->logger->disable();
		
		$this->assertFalse($this->entity->setPluginSetting('test_plugin', 'invalid_value', $value));
		
		_elgg_services()->logger->enable();
	}
	
	/**
	 * @dataProvider invalidPluginSettingValueProvider
	 */
	public function testUseHookToConvertInvalidPluginSettingValue($invalid_value) {
		$plugin_hook = $this->registerTestingHook('plugin_setting', $this->entity->getType(), function(\Elgg\Hook $hook) {
			return serialize($hook->getValue());
		});
		
		$this->assertTrue($this->entity->setPluginSetting('test_plugin', 'foo', $invalid_value));
		$plugin_hook->assertNumberOfCalls(1);
		$plugin_hook->assertValueBefore($invalid_value);
		$plugin_hook->assertValueAfter(serialize($invalid_value));
		
		$this->assertEquals(serialize($invalid_value), $this->entity->getPluginSetting('test_plugin', 'foo'));
	}
	
	public function invalidPluginSettingValueProvider() {
		return [
			[new \stdClass()],
			[['a', 'b']],
		];
	}
}
