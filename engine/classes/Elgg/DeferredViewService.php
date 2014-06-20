<?php
namespace Elgg;

use Elgg\DeferredViews\View;

/**
 * Manager of deferred rendering views. This keeps track of views that have been deferred.
 */
class DeferredViewService {

	static protected $global_counter = 0;

	/**
	 * @var View[]
	 */
	protected $views = array();

	/**
	 * @var string
	 */
	protected $placeholder_format = '';

	/**
	 * Constructor
	 *
	 * @param string $unique_token Unique alphanumeric token. If end users can guess this they may trick the system
	 *                             into rendering a view where it was not intended.
	 */
	public function __construct($unique_token) {
		$this->placeholder_format = "{$unique_token}z%dz";
	}

	/**
	 * Get a token that will be later replaced by the output of the renderer
	 *
	 * @param string $view_name Name of the view we're deferring
	 * @param array  $args      Args passed to the view
	 * @param string $viewtype  The viewtype for elgg_view()
	 *
	 * @return string Token
	 */
	public function defer($view_name, array $args = array(), $viewtype = '') {
		self::$global_counter += 1;
		$placeholder = sprintf($this->placeholder_format, self::$global_counter);

		// we need to capture the viewtype now, when the placeholder is created.
		if (!$viewtype) {
			$viewtype = elgg_get_viewtype();
		}

		$view = new View($view_name, $args, $viewtype);
		$this->views[$placeholder] = $view;

		return $placeholder;
	}

	/**
	 * Replace all deferred view placeholders in $input
	 *
	 * @param string $output The current output containing view placeholders
	 *
	 * @return string
	 */
	public function resolveAll($output) {
		foreach ($this->views as $placeholder => $view) {

			if (false === strpos($output, $placeholder)) {
				// no placeholder, let's put off rendering it. maybe we won't have to!
				continue;
			}

			$output = str_replace($placeholder, $view->render(), $output, $count);
			if ($count) {
				// we can forget views once they're resolved
				unset($this->views[$placeholder]);
			}
		}

		return $output;
	}
}
