<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Hook;

/**
 * Plugin Hooks
 *
 * Use elgg()->hooks
 */
class PluginHooksService extends HooksRegistrationService {

	/**
	 * @var EventsService
	 */
	private $events;

	/**
	 * Constructor
	 *
	 * @param EventsService $events Events
	 *
	 * @access private
	 * @internal
	 */
	public function __construct(EventsService $events) {
		$this->events = $events;
	}

	/**
	 * Get the events API
	 *
	 * @return EventsService
	 * @deprecated 3.0
	 */
	public function getEvents() {
		_elgg_services()->deprecation->sendNotice(__METHOD__ . " has been deprecated, please use elgg()->events", '3.0');
		
		return $this->events;
	}

	/**
	 * Triggers a plugin hook
	 *
	 * @param string $name   The name of the plugin hook
	 * @param string $type   The type of the plugin hook
	 * @param mixed  $params Supplied params for the hook
	 * @param mixed  $value  The value of the hook, this can be altered by registered callbacks
	 *
	 * @return mixed
	 *
	 * @see elgg_trigger_plugin_hook()
	 */
	public function trigger($name, $type, $params = null, $value = null) {

		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		$hook = 'hook';
		/* @var Hook|string $hook */

		$handlers = $this->events->getHandlersService();

		foreach ($this->getOrderedHandlers($name, $type) as $handler) {
			$exit_warning = null;

			if (in_array($name, ['forward', 'action', 'route'])) {
				// assume the handler is going to exit the request...
				$exit_warning = function () use ($name, $type, $handler, $handlers) {
					_elgg_services()->deprecation->sendNotice(
						"'$name', '$type' plugin hook should not be used to serve a response. Instead return an "
						. "appropriate ResponseBuilder instance from an action or page handler. Do not terminate "
						. "code execution with exit() or die() in {$handlers->describeCallable($handler)}",
						'2.3'
					);
				};
				$this->events->registerHandler('shutdown', 'system', $exit_warning);
			}

			list($success, $return, $hook) = $handlers->call($handler, $hook, [$name, $type, $value, $params]);

			if ($exit_warning) {
				// an exit did not occur, so no need for the warning...
				$this->events->unregisterHandler('shutdown', 'system', $exit_warning);
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
			$type = ViewsService::canonicalizeViewName($type);
		}

		return parent::registerHandler($name, $type, $callback, $priority);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function unregisterHandler($name, $type, $callback) {
		if (($name == 'view' || $name == 'view_vars') && $type != 'all') {
			$type = ViewsService::canonicalizeViewName($type);
		}
		
		return parent::unregisterHandler($name, $type, $callback);
	}
}
