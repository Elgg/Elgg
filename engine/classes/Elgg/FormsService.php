<?php

namespace Elgg;

use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\LogicException;
use Elgg\Forms\FieldsService;
use Elgg\Javascript\ESMService;
use Elgg\Traits\Loggable;

/**
 * Forms service
 *
 * @internal
 * @since 2.3
 */
class FormsService {

	use Loggable;

	protected bool $rendering = false;

	protected string $footer = '';

	/**
	 * Constructor
	 *
	 * @param ViewsService  $views  Views service
	 * @param EventsService $events Events service
	 * @param ESMService    $esm    ESM service
	 * @param FieldsService $fields Fields service
	 */
	public function __construct(
		protected ViewsService $views,
		protected EventsService $events,
		protected ESMService $esm,
		protected FieldsService $fields,
	) {
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
	 *                          - 'ajax' bool If true, the form will be submitted with an ajax request
	 * @param array  $body_vars $vars passed to the "forms/<action>" view
	 *
	 * @return string The complete form
	 */
	public function render(string $action, array $form_vars = [], array $body_vars = []): string {
		$defaults = [
			'action' => elgg_generate_action_url($action, [], false),
			'method' => 'post',
			'ajax' => false,
			'sticky_enabled' => false,
			'sticky_form_name' => $action,
			'sticky_ignored_fields' => [],
		];

		// append elgg-form class to any class options set
		$form_vars['class'] = elgg_extract_class($form_vars, [
			'elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $action)],
		);
		
		$form_vars = array_merge($defaults, $form_vars);

		if (!isset($form_vars['enctype']) && strtolower($form_vars['method']) == 'post') {
			$form_vars['enctype'] = 'multipart/form-data';
		}

		if (elgg_extract('ajax', $form_vars)) {
			$this->esm->import('input/form-ajax');
			$form_vars['class'][] = 'elgg-js-ajax-form';
			unset($form_vars['ajax']);
		}

		$form_vars['action_name'] = $action;
		
		$form_vars['ignore_empty_body'] = (bool) elgg_extract('ignore_empty_body', $form_vars, false);
		
		$form_vars['prevent_double_submit'] = (bool) elgg_extract('prevent_double_submit', $form_vars, true);
		
		if (!isset($form_vars['body'])) {
			// prepare body vars
			$body_vars = (array) $this->events->triggerResults('form:prepare:fields', $action, $form_vars, $body_vars);
			
			$this->rendering = true;
			$this->footer = '';

			// Render form body
			$body = $this->views->renderView("forms/{$action}", $body_vars);

			if (!empty($body)) {
				// wrap form body
				$body = $this->views->renderView('elements/forms/body', [
					'body' => $body,
					'action_name' => $action,
					'body_vars' => $body_vars,
					'form_vars' => $form_vars,
				]);
				
				// Grab the footer if one was set during form rendering
				$body .= $this->views->renderView('elements/forms/footer', [
					'footer' => $this->getFooter(),
					'action_name' => $action,
					'body_vars' => $body_vars,
					'form_vars' => $form_vars,
				]);
			}
			
			$this->rendering = false;

			$form_vars['body'] = $body;
		}

		return elgg_view('input/form', $form_vars);
	}
	
	/**
	 * Render an entity edit/add form
	 *
	 * @param string           $entity_type    entity type
	 * @param string           $entity_subtype entity subtype
	 * @param \ElggEntity|null $entity         entity
	 * @param array            $vars           Additional vars:
	 *                                         - (string) action: which form action to use
	 *                                         - (array) body_vars: additional body vars
	 *                                         - (array) form_vars: additional form vars
	 *                                         - (string) view: which form view to use
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 * @since 7.1
	 */
	public function renderEntity(string $entity_type, string $entity_subtype, ?\ElggEntity $entity = null, array $vars = []): string {
		if ($entity instanceof \ElggEntity && ($entity->getType() !== $entity_type || $entity->getSubtype() !== $entity_subtype)) {
			throw new InvalidArgumentException("The provided entity doesn't match the given entity type/subtype");
		}
		
		$view = (string) elgg_extract('view', $vars, $this->getFormView($entity_type, $entity_subtype));
		$action = (string) elgg_extract('action', $vars, $this->getFormAction($entity_type, $entity_subtype));
		
		$fields = $this->fields->get($entity_type, $entity_subtype);
		$fields = (array) $this->events->triggerResults('form:register:fields', "{$entity_type}:{$entity_subtype}", [
			'entity' => $entity,
			'entity_type' => $entity_type,
			'entity_subtype' => $entity_subtype,
		], $fields);
		
		$default_form_vars = [
			'sticky_enabled' => true,
			'action' => $action,
		];
		$form_vars = array_merge((array) elgg_extract('form_vars', $vars), $default_form_vars);
		
		$default_body_vars = [
			'entity_type' => $entity_type,
			'entity_subtype' => $entity_subtype,
			'entity' => $entity,
			'fields' => $fields,
		];
		$body_vars = array_merge((array) elgg_extract('body_vars', $vars), $default_body_vars);
		
		return $this->render($view, $form_vars, $body_vars);
	}
	
	/**
	 * Get the form view for edit/add of an entity type/subtype
	 *
	 * @param string $entity_type    entity type
	 * @param string $entity_subtype entity subtype
	 *
	 * @return string
	 * @since 7.1
	 */
	protected function getFormView(string $entity_type, string $entity_subtype): string {
		$forms = [
			"{$entity_type}/{$entity_subtype}/edit",
			"{$entity_subtype}/edit",
		];
		
		foreach ($forms as $view) {
			if (elgg_view_exists("forms/{$view}")) {
				return $view;
			}
		}
		
		return 'entity/edit';
	}
	
	/**
	 * Get the form action for edit/add of an entity type/subtype
	 *
	 * @param string $entity_type    entity type
	 * @param string $entity_subtype entity subtype
	 *
	 * @return string
	 * @throws DomainException
	 * @since 7.1
	 */
	protected function getFormAction(string $entity_type, string $entity_subtype): string {
		$actions = [
			"{$entity_type}/{$entity_subtype}/edit",
			"{$entity_subtype}/edit",
		];
		
		foreach ($actions as $action) {
			if (elgg_action_exists($action)) {
				return elgg_generate_action_url($action, [], false);
			}
		}
		
		throw new DomainException("No form action found for {$entity_type}/{$entity_subtype}");
	}

	/**
	 * Sets form footer and defers its rendering until the form view and extensions have been rendered.
	 * Deferring footer rendering allows plugins to extend the form view while maintaining
	 * logical DOM structure.
	 * Footer will be rendered using 'elements/forms/footer' view after form body has finished rendering
	 *
	 * @param string $footer Footer
	 * @return void
	 * @throws LogicException
	 */
	public function setFooter(string $footer = ''): void {
		if (!$this->rendering) {
			throw new LogicException('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view events)');
		}

		$this->footer = $footer;
	}

	/**
	 * Returns currently set footer
	 *
	 * @return string
	 * @throws LogicException
	 */
	public function getFooter(): string {
		if (!$this->rendering) {
			throw new LogicException('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view events)');
		}

		return $this->footer;
	}
}
