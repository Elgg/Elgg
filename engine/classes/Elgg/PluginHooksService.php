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
	public function trigger($name, $type, $params = null, $value = null) {

		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		$hook = 'hook';
		/* @var Hook|string $hook */

		$handlers_svc = _elgg_services()->handlers;

		foreach ($this->getOrderedHandlers($name, $type) as $handler) {
			$exit_warning = null;

			if (in_array($name, ['forward', 'action', 'route'])) {
				// assume the handler is going to exit the request...
				$exit_warning = function () use ($name, $type, $handler, $handlers_svc) {
					_elgg_services()->deprecation->sendNotice(
						"'$name', '$type' plugin hook should not be used to serve a response. Instead return an "
						. "appropriate ResponseBuilder instance from an action or page handler. Do not terminate "
						. "code execution with exit() or die() in {$handlers_svc->describeCallable($handler)}",
						'2.3'
					);
				};
				_elgg_services()->events->registerHandler('shutdown', 'system', $exit_warning);
			}

			list($success, $return, $hook) = $handlers_svc->call($handler, $hook, [$name, $type, $value, $params]);

			if ($exit_warning) {
				// an exit did not occur, so no need for the warning...
				_elgg_services()->events->unregisterHandler('shutdown', 'system', $exit_warning);
			}

			if (!$success) {
				continue;
			}
			if ($return !== null) {
				$value = $return;
				if ($hook instanceof Hook) {
					$hook->setValue($return);
				}
			}
		}

		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (($name == 'view' || $name == 'view_vars') && $type !== 'all') {
			$type = _elgg_services()->views->canonicalizeViewName($type);
		}

		return parent::registerHandler($name, $type, $callback, $priority);
	}
}
