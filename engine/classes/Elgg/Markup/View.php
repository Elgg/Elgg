<?php

namespace Elgg\Markup;

/**
 * View element helper
 */
class View implements Element {

	/**
	 * @var string
	 */
	protected $view;
	/**
	 * @var array
	 */
	protected $vars;
	/**
	 * @var string
	 */
	protected $viewtype;

	/**
	 * Constructor
	 *
	 * @param string $view     View name
	 * @param array  $vars     View vars
	 * @param string $viewtype Viewtype
	 */
	public function __construct($view, array $vars = [], $viewtype = '') {
		$this->view = $view;
		$this->vars = $vars;
		$this->viewtype = $viewtype;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render(array $options = []) {
		$params = $this->vars;
		$params['#options'] = $options;

		return elgg_view($this->view, $this->vars, $this->viewtype);
	}
}