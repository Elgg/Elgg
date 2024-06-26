<?php

namespace Elgg\Menu;

/**
 * A complete menu, sorted, filtered by the "prepare" event, and split into sections.
 *
 * This also encapsulates parameters to be passed to views.
 */
class Menu {

	/**
	 * Constructor
	 *
	 * @param array $params Params. Must include:
	 *                      "name" menu name
	 *                      "menu" array of sections (each an array of items)
	 */
	public function __construct(protected array $params) {
	}

	/**
	 * Get all menu sections
	 *
	 * @return PreparedMenu
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
	 * @return MenuSection|null
	 */
	public function getSection($name, $default = null) {
		return $this->params['menu'][$name] ?? $default;
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
