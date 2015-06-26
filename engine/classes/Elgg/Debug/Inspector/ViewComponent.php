<?php

namespace Elgg\Debug\Inspector;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package Elgg.Core
 * @since   1.11
 */
class ViewComponent {

	/**
	 * @var string View name. E.g. "elgg.css"
	 */
	public $view;

	/**
	 * @var string View file extension, if known. E.g. "php"
	 */
	public $extension = null;

	/**
	 * @var bool Is this component represent an overridden view?
	 */
	public $overridden = false;

	/**
	 * @var string Full file path, if available
	 */
	public $file = '';
}
