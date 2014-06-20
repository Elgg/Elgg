<?php
namespace Elgg\DeferredViews;

/**
 * A single unresolved view rendering. It outputs a token during initial rendering but when
 * forced to resolve, it can render itself and replace instances of the token.
 */
class View {

	/**
	 * @var string The view name
	 */
	protected $view;

	/**
	 * @var array
	 */
	protected $args;

	/**
	 * @var string
	 */
	protected $viewtype;

	/**
	 * @var string[]
	 */
	protected $contexts;

	/**
	 * Constructor
	 *
	 * @param string $view     Name of the view we're deferring
	 * @param array  $args     Args passed to the view or the callable object
	 * @param string $viewtype The viewtype for elgg_view()
	 */
	public function __construct($view, array $args, $viewtype) {
		$this->view = $view;
		$this->args = $args;
		$this->viewtype = $viewtype;

		$this->contexts = _elgg_services()->context->getAll();
	}

	/**
	 * Render the deferred view
	 *
	 * @return string
	 */
	public function render() {
		$context_svc = _elgg_services()->context;
		$old_contexts = $context_svc->getAll();

		// restore context when placeholder was rendered
		$context_svc->setAll($this->contexts);

		$output = elgg_view($this->view, $this->args, false, null, $this->viewtype);

		// restore global context
		$context_svc->setAll($old_contexts);

		return $output;
	}
}
