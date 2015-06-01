<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Hook;

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
	public function trigger($name, $type, $params = null, $returnvalue = null) {
		$hooks = $this->getOrderedHandlers($name, $type);

		// create Hook on demand
		/* @var Hook $hook */
		$hook = null;

		foreach ($hooks as $callback) {
			// for perf, shortcut on valid callables
			if (is_callable($callback)) {
				// old API
				$args = array($name, $type, $returnvalue, $params);
				$temp_return_value = call_user_func_array($callback, $args);
				if (!is_null($temp_return_value)) {
					$returnvalue = $temp_return_value;
				}
				continue;
			}

			$orig_callback = $callback;

			$callback = _elgg_services()->handlers->resolveCallable($callback);
			if (!$callback) {
				if ($this->logger) {
					$this->logger->warn("handler for plugin hook [$name, $type] is not callable: "
						. _elgg_services()->handlers->describeCallable($orig_callback));
				}
				continue;
			}

			if ($hook === null) {
				$hook = new Hook(elgg(), $name, $type, $returnvalue, $params);
			} else {
				// update the value in case it's changed
				$hook->setValue($returnvalue);
			}
			$temp_return_value = call_user_func($callback, $hook);
			if ($temp_return_value !== null) {
				$returnvalue = $temp_return_value;
			}
		}
	
		return $returnvalue;
	}
}
