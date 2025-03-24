<?php

namespace Elgg\Traits\Entity;

/**
 * Handle CRUD for plugin settings
 *
 * @since 4.0
 */
trait PluginSettings {
	
	/**
	 * Save a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $value     setting value
	 *
	 * @return bool
	 */
	public function setPluginSetting(string $plugin_id, string $name, $value): bool {
		$value = _elgg_services()->events->triggerResults('plugin_setting', $this->getType(), [
			'entity' => $this,
			'plugin_id' => $plugin_id,
			'name' => $name,
			'value' => $value,
		], $value);
		
		$name = $this->getNamespacedPluginSettingName($plugin_id, $name);
		
		return elgg_call(ELGG_DISABLE_SYSTEM_LOG, function() use ($name, $value) {
			return $this->setMetadata($name, $value);
		});
	}
	
	/**
	 * Get a plugin setting.
	 * Will return $default if the plugin isn't active
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $default   default setting value
	 *
	 * @return mixed
	 */
	public function getPluginSetting(string $plugin_id, string $name, $default = null) {
		$plugin = _elgg_services()->plugins->get($plugin_id);
		if (!$plugin instanceof \ElggPlugin || !$plugin->isActive()) {
			return $default;
		}
		
		$name = $this->getNamespacedPluginSettingName($plugin_id, $name);
		
		return $this->getMetadata($name) ?? $default;
	}
	
	/**
	 * Remove a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 *
	 * @return bool
	 */
	public function removePluginSetting(string $plugin_id, string $name): bool {
		$name = $this->getNamespacedPluginSettingName($plugin_id, $name);
		
		return elgg_call(ELGG_DISABLE_SYSTEM_LOG, function() use ($name) {
			return $this->deleteMetadata($name);
		});
	}
	
	/**
	 * Get the namespaced setting name where the plugin setting is saved
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 *
	 * @return string
	 */
	final public function getNamespacedPluginSettingName(string $plugin_id, string $name): string {
		return "plugin:{$this->getType()}_setting:{$plugin_id}:{$name}";
	}
}
