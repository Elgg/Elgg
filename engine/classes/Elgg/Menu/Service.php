<?php

namespace Elgg\Menu;

use Elgg\Collections\Collection;
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
	 *
	 * @access   private
	 * @internal Do not use. Use `elgg()->menus`.
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
		$menus = $this->config->menus;

		$items = $this->prepareMenuItems(elgg_extract('items', $params, []));
		unset($params['items']);

		if ($menus && isset($menus[$name])) {
			$registered_items = elgg_extract($name, $menus, []);
			$items->merge($registered_items);
		}

		$params['name'] = $name;

		$params = $this->hooks->trigger('parameters', "menu:$name", $params, $params);

		if (!isset($params['sort_by'])) {
			$params['sort_by'] = 'priority';
		}

		$items = $this->hooks->trigger('register', "menu:$name", $params, $items);

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

		$builder = new ElggMenuBuilder($menu->getItems());
		$params['menu'] = $builder->getMenu($sort_by);
		$params['selected_item'] = $builder->getSelected();

		$params['menu'] = $this->hooks->trigger('prepare', "menu:$name", $params, $params['menu']);

		return new Menu($params);
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

			foreach ($items as $item) {
				$section = $item->getSection();
				if ($section == 'default') {
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
