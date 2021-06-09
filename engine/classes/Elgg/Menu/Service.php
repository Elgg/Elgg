<?php

namespace Elgg\Menu;

use Elgg\PluginHooksService;
use Elgg\Config;
use ElggMenuBuilder;
use ElggMenuItem;

/**
 * Methods to construct and prepare menus for rendering
 */
class Service {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks  Plugin hooks
	 * @param Config             $config Elgg config
	 */
	public function __construct(PluginHooksService $hooks, Config $config) {
		$this->hooks = $hooks;
		$this->config = $config;
	}

	/**
	 * Build a full menu, pulling items from configuration and the "register" menu hooks.
	 *
	 * Parameters are filtered by the "parameters" hook.
	 *
	 * @param string $name   Menu name
	 * @param array  $params Hook/view parameters
	 *
	 * @return Menu
	 */
	public function getMenu($name, array $params = []) {
		return $this->prepareMenu($this->getUnpreparedMenu($name, $params));
	}

	/**
	 * Build an unprepared menu.
	 *
	 * @param string $name   Menu name
	 * @param array  $params Hook/view parameters
	 *
	 * @return UnpreparedMenu
	 */
	public function getUnpreparedMenu($name, array $params = []) {
		$items = $this->prepareMenuItems(elgg_extract('items', $params, []));
		unset($params['items']);

		$registered_items = elgg_extract($name, $this->config->menus);
		if (is_array($registered_items)) {
			$items->merge($registered_items);
		}

		$params['name'] = $name;

		$params = $this->hooks->trigger('parameters', "menu:$name", $params, $params);

		if (!isset($params['sort_by'])) {
			$params['sort_by'] = 'priority';
		}

		// trigger specific menu hooks
		$entity = elgg_extract('entity', $params);
		if ($entity instanceof \ElggEntity) {
			$items = $this->hooks->trigger('register', "menu:{$name}:{$entity->type}:{$entity->subtype}", $params, $items);
		}

		$annotation = elgg_extract('annotation', $params);
		if ($annotation instanceof \ElggAnnotation) {
			$items = $this->hooks->trigger('register', "menu:{$name}:{$annotation->getType()}:{$annotation->getSubtype()}", $params, $items);
		}

		$relationship = elgg_extract('relationship', $params);
		if ($relationship instanceof \ElggRelationship) {
			$items = $this->hooks->trigger('register', "menu:{$name}:{$relationship->getType()}:{$relationship->getSubtype()}", $params, $items);
		}

		// trigger generic menu hook
		$items = $this->hooks->trigger('register', "menu:{$name}", $params, $items);

		return new UnpreparedMenu($params, $items);
	}

	/**
	 * Split a menu into sections, and pass it through the "prepare" hook
	 *
	 * @param UnpreparedMenu $menu Menu
	 *
	 * @return Menu
	 */
	public function prepareMenu(UnpreparedMenu $menu) {
		$name = $menu->getName();
		$params = $menu->getParams();
		$sort_by = $menu->getSortBy();
		$selected_menu_item_name = elgg_extract('selected_item_name', $params, '');
		
		$builder = new ElggMenuBuilder($menu->getItems());
		$builder->setSelected($selected_menu_item_name);
		
		$params['menu'] = $builder->getMenu($sort_by);
		$params['selected_item'] = $builder->getSelected();

		// trigger specific menu hooks
		$entity = elgg_extract('entity', $params);
		if ($entity instanceof \ElggEntity) {
			$params['menu'] = $this->hooks->trigger('prepare', "menu:{$name}:{$entity->type}:{$entity->subtype}", $params, $params['menu']);
		}

		$annotation = elgg_extract('annotation', $params);
		if ($annotation instanceof \ElggAnnotation) {
			$params['menu'] = $this->hooks->trigger('prepare', "menu:{$name}:{$annotation->getType()}:{$annotation->getSubtype()}", $params, $params['menu']);
		}

		$relationship = elgg_extract('relationship', $params);
		if ($relationship instanceof \ElggRelationship) {
			$params['menu'] = $this->hooks->trigger('prepare', "menu:{$name}:{$relationship->getType()}:{$relationship->getSubtype()}", $params, $params['menu']);
		}

		// trigger generic menu hook
		$params['menu'] = $this->hooks->trigger('prepare', "menu:$name", $params, $params['menu']);
		
		$params['menu'] = $this->prepareVerticalMenu($params['menu'], $params);
		$params['menu'] = $this->prepareDropdownMenu($params['menu'], $params);
		$params['menu'] = $this->prepareSelectedParents($params['menu'], $params);
		
		return new Menu($params);
	}
	
	/**
	 * Prepares a vertical menu by setting the display child menu option to "toggle" if not set
	 *
	 * @param PreparedMenu $menu   the current prepared menu
	 * @param array        $params the menu params
	 *
	 * @return \Elgg\Menu\PreparedMenu
	 */
	protected function prepareVerticalMenu(PreparedMenu $menu, array $params) {
		if (elgg_extract('prepare_vertical', $params) !== true) {
			return $menu;
		}
		
		$prepare = function(\ElggMenuItem $menu_item) use (&$prepare) {
			$child_menu_vars = $menu_item->getChildMenuOptions();
			if (empty($child_menu_vars['display'])) {
				$child_menu_vars['display'] = 'toggle';
			}
			$menu_item->setChildMenuOptions($child_menu_vars);
			
			foreach ($menu_item->getChildren() as $child_menu_item) {
				$prepare($child_menu_item);
			}
		};
		
		foreach ($menu as $menu_items) {
			foreach ($menu_items as $menu_item) {
				if ($menu_item instanceof \ElggMenuItem) {
					$prepare($menu_item);
				}
			}
		}
		
		return $menu;
	}

	/**
	 * Marks parents of selected items also as selected
	 *
	 * @param PreparedMenu $menu   the current prepared menu
	 * @param array        $params the menu params
	 *
	 * @return \Elgg\Menu\PreparedMenu
	 */
	protected function prepareSelectedParents(PreparedMenu $menu, array $params) {
		$selected_item = elgg_extract('selected_item', $params);
		if (!$selected_item instanceof \ElggMenuItem) {
			return $menu;
		}
		
		$parent = $selected_item->getParent();
		while ($parent instanceof \ElggMenuItem) {
			$parent->setSelected();
			$parent->addItemClass('elgg-has-selected-child');
			$parent = $parent->getParent();
		}
	
		return $menu;
	}

	/**
	 * Prepares a dropdown menu
	 *
	 * @param PreparedMenu $menu   the current prepared menu
	 * @param array        $params the menu params
	 *
	 * @return \Elgg\Menu\PreparedMenu
	 */
	protected function prepareDropdownMenu(PreparedMenu $menu, array $params) {
		if (elgg_extract('prepare_dropdown', $params) !== true) {
			return $menu;
		}
		
		$items = $menu->getItems('default');
		if (empty($items)) {
			return $menu;
		}
		
		$menu_name = elgg_extract('name', $params);
		$menu->getSection('default')->fill([
			\ElggMenuItem::factory([
				'name' => 'entity-menu-toggle',
				'icon' => 'ellipsis-v',
				'href' => false,
				'text' => '',
				'child_menu' => [
					'display' => 'dropdown',
					'data-position' => json_encode([
						'at' => 'right bottom',
						'my' => 'right top',
						'collision' => 'fit fit',
					]),
					'class' => "elgg-{$menu_name}-dropdown-menu",
				],
				'children' => $items,
			]),
		]);
		
		return $menu;
	}

	/**
	 * Combine several menus into one
	 *
	 * Unprepared menus will be built separately, then combined, with items reassigned to sections
	 * named after their origin menu. The returned menu must be prepared before display.
	 *
	 * @param string[] $names    Menu names
	 * @param array    $params   Menu params
	 * @param string   $new_name Combined menu name (used for the prepare hook)
	 *
	 * @return UnpreparedMenu
	 */
	public function combineMenus(array $names = [], array $params = [], $new_name = '') {
		if (!$new_name) {
			$new_name = implode('__', $names);
		}

		$all_items = new MenuItems();

		foreach ($names as $name) {
			$items = $this->getUnpreparedMenu($name, $params)->getItems();

			/* @var $item \ElggMenuItem */
			foreach ($items as $item) {
				$section = $item->getSection();
				if ($section === 'default') {
					$item->setSection($name);
				}
				$item->setData('menu_name', $name);

				$all_items->add($item);
			}
		}

		$params['name'] = $new_name;

		return new UnpreparedMenu($params, $all_items);
	}

	/**
	 * Prepare menu items
	 *
	 * @param array $items An array of ElggMenuItem instances or menu item factory options
	 *
	 * @return MenuItems
	 */
	public function prepareMenuItems($items = []) {
		$prepared_items = new MenuItems();

		foreach ($items as $item) {
			if (is_array($item)) {
				$options = $item;
				$item = ElggMenuItem::factory($options);
			}

			if (!$item instanceof ElggMenuItem) {
				continue;
			}

			$prepared_items->add($item);
		}

		return $prepared_items;
	}
}
