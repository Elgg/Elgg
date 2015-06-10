<?php
namespace Elgg;
use Elgg\Debug\Inspector;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Use the elgg_* versions instead.
 * 
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
class PluginHooksService extends \Elgg\HooksRegistrationService {

	/**
	 * Triggers a plugin hook
	 *
	 * @see elgg_trigger_plugin_hook
	 * @access private
	 */
	public function trigger($hook, $type, $params = null, $returnvalue = null) {
		$hooks = $this->getOrderedHandlers($hook, $type);
		
		foreach ($hooks as $callback) {
			if (!is_callable($callback)) {
				if ($this->logger) {
					$inspector = new Inspector();
					$this->logger->warn("handler for plugin hook [$hook, $type] is not callable: "
										. $inspector->describeCallable($callback));
				}
				continue;
			}

			$args = array($hook, $type, $returnvalue, $params);
			$temp_return_value = call_user_func_array($callback, $args);
			if (!is_null($temp_return_value)) {
				$returnvalue = $temp_return_value;
			}
		}
	
		return $returnvalue;
	}

	/**
	 * Filter a value by passing it through the 'filter' plugin hook
	 *
	 * @param string $type   Hook type
	 * @param mixed  $value  Value filtered
	 * @param mixed  $params Hook params
	 *
	 * @return mixed
	 * @see elgg_filter
	 * @since 1.12
	 */
	public function filter($type, $value, $params = null) {
		return $this->trigger('filter', $type, $params, $value);
	}
}
