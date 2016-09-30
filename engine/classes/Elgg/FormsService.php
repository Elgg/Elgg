<?php

namespace Elgg;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since 2.3
 */
class FormsService {

	/**
	 * @var ViewsService
	 */
	private $views;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var bool
	 */
	private $rendering;

	/**
	 * @var string
	 */
	private $footer = '';

	/**
	 * Constructor
	 *
	 * @param ViewsService $views  Views service
	 * @param Logger       $logger Logger service
	 */
	public function __construct(ViewsService $views, Logger $logger) {
		$this->views = $views;
		$this->logger = $logger;
	}

	/**
	 * Convenience function for generating a form from a view in a standard location.
	 *
	 * This function assumes that the body of the form is located at "forms/$action" and
	 * sets the action by default to "action/$action".  Automatically wraps the forms/$action
	 * view with a <form> tag and inserts the anti-csrf security tokens.
	 *
	 * @tip This automatically appends elgg-form-action-name to the form's class. It replaces any
	 * slashes with dashes (blog/save becomes elgg-form-blog-save)
	 *
	 * @example
	 * <code>echo elgg_view_form('login');</code>
	 *
	 * This would assume a "login" form body to be at "forms/login" and would set the action
	 * of the form to "http://yoursite.com/action/login".
	 *
	 * If elgg_view('forms/login') is:
	 * <input type="text" name="username" />
	 * <input type="password" name="password" />
	 *
	 * Then elgg_view_form('login') generates:
	 * <form action="http://yoursite.com/action/login" method="post">
	 *     ...security tokens...
	 *     <input type="text" name="username" />
	 *     <input type="password" name="password" />
	 * </form>
	 *
	 * @param string $action    The name of the action. An action name does not include
	 *                          the leading "action/". For example, "login" is an action name.
	 * @param array  $form_vars $vars passed to the "input/form" view
	 * @param array  $body_vars $vars passed to the "forms/<action>" view
	 *
	 * @return string The complete form
	 */
	public function render($action, $form_vars = array(), $body_vars = array()) {

		$defaults = array(
			'action' => elgg_normalize_url("action/$action"),
		);

		// append elgg-form class to any class options set
		$form_vars['class'] = (array) elgg_extract('class', $form_vars, []);
		$form_vars['class'][] = 'elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $action);

		$form_vars = array_merge($defaults, $form_vars);

		$form_vars['action_name'] = $action;
		
		if (!isset($form_vars['body'])) {
			$this->rendering = true;
			$this->footer = '';

			// Render form body
			$body = $this->views->renderView("forms/$action", $body_vars);

			if (!empty($body)) {
				// Grab the footer if one was set during form rendering
				$body .= $this->views->renderView('elements/forms/footer', [
					'footer' => $this->getFooter(),
					'action_name' => $action,
				]);
			}
			
			$this->rendering = false;

			$form_vars['body'] = $body;
		}

		return elgg_view('input/form', $form_vars);
	}

	/**
	 * Sets form footer and defers its rendering until the form view and extensions have been rendered.
	 * Deferring footer rendering allows plugins to extend the form view while maintaining
	 * logical DOM structure.
	 * Footer will be rendered using 'elements/forms/footer' view after form body has finished rendering
	 *
	 * @param string $footer Footer
	 * @return bool
	 */
	public function setFooter($footer = '') {

		if (!$this->rendering) {
			$this->logger->error('Form footer can only be set and retrieved during form rendering, '
					. 'anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)');
			return false;
		}

		$this->footer = $footer;
		return true;
	}

	/**
	 * Returns currently set footer, or false if not in the form rendering stack
	 * @return string|false
	 */
	public function getFooter() {
		if (!$this->rendering) {
			$this->logger->error('Form footer can only be set and retrieved during form rendering, '
					. 'anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)');
			return false;
		}

		return $this->footer;
	}

}
