<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Some logic implemented before action is executed
 */
class ActionMiddleware {

	/**
	 * @var \Elgg\Request
	 */
	protected $request;
	
	/**
	 * @var string
	 */
	protected $form_name;
	
	/**
	 * Pre-action logic
	 *
	 * @param \Elgg\Request $request Request
	 *
	 * @return void
	 * @throws ValidationException
	 */
	public function __invoke(\Elgg\Request $request): void {
		$this->request = $request;
		
		$route = $request->getRoute();
		list(, $action) = explode(':', $route, 2);

		// save sticky form values
		$this->prepareStickyForm();
		
		$result = $request->elgg()->events->triggerResults('action:validate', $action, ['request' => $request], true);
		if ($result === false) {
			throw new ValidationException(elgg_echo('ValidationException'));
		}

		// set the maximum execution time for actions
		$action_timeout = $request->elgg()->config->action_time_limit;
		if (isset($action_timeout)) {
			set_time_limit($action_timeout);
		}
	}
	
	/**
	 * Save the action input in sticky form values
	 *
	 * @return void
	 * @since 5.0
	 */
	protected function prepareStickyForm(): void {
		$this->form_name = $this->request->getParam('_elgg_sticky_form_name');
		
		if (empty($this->form_name)) {
			return;
		}
		
		// add user and system ignored fields
		$ignored_fields = (string) $this->request->getParam('_elgg_sticky_ignored_fields');
		$ignored_fields = elgg_string_to_array($ignored_fields);
		
		_elgg_services()->stickyForms->makeStickyForm($this->form_name, $ignored_fields);
		
		// register sticky value cleanup
		$this->request->elgg()->events->registerHandler('response', 'all', [$this, 'cleanupStickyValues']);
	}
	
	/**
	 * Automatically cleanup sticky form values after a successfull action
	 *
	 * @param \Elgg\Event $event 'response', 'action:all'
	 *
	 * @return void
	 * @since 5.0
	 */
	public function cleanupStickyValues(\Elgg\Event $event): void {
		$response = $event->getValue();
		if (!$response instanceof OkResponse) {
			return;
		}
		
		_elgg_services()->stickyForms->clearStickyForm($this->form_name);
	}
}
