<?php
/**
 * Elgg Menu
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 * @since      2.2.0
 */
class ElggMenu {

	protected $menu_name = null;

	protected $vars = [];

	/**
	 * @var \ElggMenuItem[]
	 */
	protected $menu = [];

	/**
	 * @var \ElggMenuBuilder
	 */
	protected $builder = null;


	/**
	 * \ElggMenuBuilder constructor
	 *
	 * @param string $menu_name Name of the menu
	 * @param array  $vars      Array of vars to pass to the menu hooks
	 *
	 * @return void
	 */
	public function __construct($menu_name, $vars) {
		global $CONFIG;
		
		if (isset($CONFIG->menus[$menu_name])) {
			$this->menu = $CONFIG->menus[$menu_name];
		}
		
		$this->menu_name = $menu_name;
		$this->vars = $vars;
		
		// Give plugins a chance to add menu items just before creation.
		// This supports dynamic menus (example: user_hover).
		$this->menu = elgg_trigger_plugin_hook('register', "menu:{$this->menu_name}", $this->vars, $this->menu);
		
		$this->builder = new \ElggMenuBuilder($this->menu);
	}

	/**
	 * Get a prepared menu array
	 *
	 * @param mixed $sort_by Method to sort the menu by. @see \ElggMenuBuilder::sort()
	 *
	 * @return array
	 */
	public function getMenu($sort_by = 'text') {

		$menu = $this->builder->getMenu($sort_by);
		
		return elgg_trigger_plugin_hook('prepare', "menu:{$this->menu_name}", $this->vars, $menu);
	}

	/**
	 * Get a unprepared array of menuitems
	 *
	 * @param mixed  $sort_by Method to sort the menu by. @see \ElggMenuBuilder::sort()
	 * @param string $section (optional) the section of the menu to return the menu items for
	 *
	 * @return array
	 */
	public function getMenuItems($sort_by = 'text', $section = null) {

		$menu = $this->builder->getMenu($sort_by);
		
		if (!isset($section)) {
			return $menu;
		}
		
		return elgg_extract($section, $menu);
	}

	/**
	 * Get the selected menu item
	 *
	 * @return \ElggMenuItem
	 */
	public function getSelected() {
		return $this->builder->getSelected();
	}
}
