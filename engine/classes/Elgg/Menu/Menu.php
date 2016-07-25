<?php
namespace Elgg\Menu;

use ElggMenuItem;

/**
 * A complete menu, sorted, filtered by the "prepare" hook, and split into sections.
 *
 * This also encapsulates parameters to be passed to views.
 */
class Menu {

	/**
	 * @var array
	 */
	private $params;

	/**
	 * Constructor
	 *
	 * @param array $params Params. Must include:
	 *                      "name" menu name
	 *                      "menu" array of sections (each an array of items)
	 * @access private
	 * @internal Do not use. Use the `elgg()->menus` service methods instead.
	 */
	public function __construct(array $params) {
		$this->params = $params;
	}

	/**
	 * Get all menu sections
	 *
	 * @return ElggMenuItem[][]
	 */
	public function getSections() {
		return $this->params['menu'];
	}

	/**
	 * Get a single menu section
	 *
	 * @param string $name    Section name
	 * @param mixed  $default Value to return if section is not found
	 *
	 * @return ElggMenuItem[]|null
	 */
	public function getSection($name, $default = null) {
		return isset($this->params['menu'][$name]) ? $this->params['menu'][$name] : $default;
	}

	/**
	 * Get the menu's name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->params['name'];
	}

	/**
	 * Get the menu parameters
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}
}
