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
class PluginHooksService extends HooksRegistrationService {

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
			if (!is_callable($callback)) {
				$orig_callback = $callback;

				$callback = _elgg_services()->handlers->resolveCallable($callback);
				if (!$callback instanceof HookHandler) {
					if ($this->logger) {
						$msg = "Handler for plugin hook [$name, $type] is not callable nor the name of a class"
							. " that implements " . HookHandler::class . ": "
							. _elgg_services()->handlers->describeCallable($orig_callback);
						$this->logger->warn($msg);
					}
					continue;
				}
			}

			if ($callback instanceof HookHandler) {
				if ($hook === null) {
					$hook = new Hook(elgg(), $name, $type, $returnvalue, $params);
				} else {
					// update the value in case it's changed
					$hook->setValue($returnvalue);
				}
				$temp_return_value = call_user_func($callback, $hook);
			} else {
				// old API
				$args = array($name, $type, $returnvalue, $params);
				$temp_return_value = call_user_func_array($callback, $args);
			}

			if ($temp_return_value !== null) {
				$returnvalue = $temp_return_value;
			}
		}
	
		return $returnvalue;
	}
}
