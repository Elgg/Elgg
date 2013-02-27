<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Use the elgg_* versions instead.
 * 
 * @since 1.9.0
 * @access private
 */
class ElggPluginHookService extends ElggPluginAPIService {

	/**
	 * Triggers a plugin hook
	 *
	 * @see elgg_trigger_plugin_hook
	 * @since 1.9.0
	 * @access private
	 */
	function trigger($hook, $type, $params = null, $returnvalue = null) {
		$hooks = $this->getOrderedHandlers($hook, $type);
		
		foreach ($hooks as $callback) {
			if (is_callable($callback)) {
				$args = array($hook, $type, $returnvalue, $params);
				$temp_return_value = call_user_func_array($callback, $args);
				if (!is_null($temp_return_value)) {
					$returnvalue = $temp_return_value;
				}
			}
		}
	
		return $returnvalue;
	}
}