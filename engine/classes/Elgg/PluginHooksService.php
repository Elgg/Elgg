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

			$callback_copy = $callback;

			if (is_string($callback)
					&& false === strpos($callback, '::')
					&& !function_exists($callback)
					&& class_exists($callback)) {

				$class = ltrim($callback, '\\');

				$cached = _elgg_services()->config->get("object:$class");
				if (!$cached) {
					$cached = new $class();
					_elgg_services()->config->set("object:$class", $cached);
				}
				$callback = $cached;
			}

			if (!is_callable($callback)) {
				if ($this->logger) {
					$inspector = new Inspector();
					$this->logger->warn("handler for plugin hook [$hook, $type] is not callable: "
										. $inspector->describeCallable($callback_copy));
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
}
