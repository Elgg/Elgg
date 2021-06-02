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
	 * @param mixed  $value     setting value (needs to be a scalar)
	 *
	 * @return bool
	 */
	public function setPluginSetting(string $plugin_id, string $name, $value): bool {
		$value = _elgg_services()->hooks->trigger('plugin_setting', $this->getType(), [
			'entity' => $this,
			'plugin_id' => $plugin_id,
			'name' => $name,
			'value' => $value,
		], $value);
		
		if (isset($value) && !is_scalar($value)) {
			elgg_log("Invalid value type provided to save plugin setting '{$name}' for plugin '{$plugin_id}' only scalars are allowed", 'ERROR');
			return false;
		}
		
		$name = $this->getNamespacedPluginSettingName($plugin_id, $name);
		
		return $this->setPrivateSetting($name, $value);
	}
	
	/**
	 * Get a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $default   default setting value (will be cast to string)
	 *
	 * @return string
	 */
	public function getPluginSetting(string $plugin_id, string $name, $default = null): string {
		$name = $this->getNamespacedPluginSettingName($plugin_id, $name);
		
		return (string) ($this->getPrivateSetting($name) ?? $default);
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
		
		return $this->removePrivateSetting($name);
	}
	
	/**
	 * Get the namespaced private setting name where the plugin setting is saved
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
