<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Use the elgg_* versions instead.
 * 
 * @since 1.9.0
 * @access private
 */
class ElggPluginHookService {
	
	
	private $hooks = array();
	
	
	/**
	 * @see elgg_trigger_plugin_hook
	 * @since 1.9.0
	 * @access private
	 */
	function trigger($hook, $type, $params = null, $returnvalue = null) {
		$hooks = array();
		if (isset($this->hooks[$hook][$type])) {
			if ($hook != 'all' && $type != 'all') {
				$hooks[] = $this->hooks[$hook][$type];
			}
		}
		if (isset($this->hooks['all'][$type])) {
			if ($type != 'all') {
				$hooks[] = $this->hooks['all'][$type];
			}
		}
		if (isset($this->hooks[$hook]['all'])) {
			if ($hook != 'all') {
				$hooks[] = $this->hooks[$hook]['all'];
			}
		}
		if (isset($this->hooks['all']['all'])) {
			$hooks[] = $this->hooks['all']['all'];
		}
	
		foreach ($hooks as $callback_list) {
			if (is_array($callback_list)) {
				foreach ($callback_list as $hookcallback) {
					if (is_callable($hookcallback)) {
						$args = array($hook, $type, $returnvalue, $params);
						$temp_return_value = call_user_func_array($hookcallback, $args);
						if (!is_null($temp_return_value)) {
							$returnvalue = $temp_return_value;
						}
					}
				}
			}
		}
	
		return $returnvalue;

	}
	
	
	/**
	 * @see elgg_register_plugin_hook_handler
	 * @since 1.9.0
	 * @access private
	 */
	function registerHandler($hook, $type, $callback, $priority = 500) {
		if (empty($hook) || empty($type) || !is_callable($callback, true)) {
			return false;
		}
	
		if (!isset($this->hooks[$hook])) {
			$this->hooks[$hook] = array();
		}
		
		if (!isset($this->hooks[$hook][$type])) {
			$this->hooks[$hook][$type] = array();
		}
		
		// Priority cannot be lower than 0
		$priority = max((int) $priority, 0);
	
		while (isset($this->hooks[$hook][$type][$priority])) {
			$priority++;
		}
		
		$this->hooks[$hook][$type][$priority] = $callback;
		ksort($this->hooks[$hook][$type]);
		
		return true;
	}
	
	
	/**
	 * @see elgg_unregister_plugin_hook_handler
	 * @since 1.9.0
	 * @access private
	 */
	function unregisterHandler($hook, $type, $callback) {
		if (isset($this->hooks[$hook]) && isset($this->hooks[$hook][$type])) {
			foreach ($this->hooks[$hook][$type] as $key => $hook_callback) {
				if ($hook_callback == $callback) {
					unset($this->hooks[$hook][$type][$key]);
				}
			}
		}
	}
	
	
	/**
	 * @since 1.9.0
	 * @access private
	 */
	static function getInstance() {
		static $instance;
		
		if (!isset($instance)) {
			$instance = new ElggPluginHookService();
		}
		
		return $instance;
	}
}