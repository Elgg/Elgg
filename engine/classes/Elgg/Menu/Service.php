<?php

namespace Elgg\Menu;

use Elgg\Config;
use Elgg\EventsService;

/**
 * Methods to construct and prepare menus for rendering
 */
class Service {

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var \ElggMenuItem[]
	 */
	protected $menus = [];

	/**
	 * Constructor
	 *
	 * @param EventsService $events Events
	 * @param Config        $config Elgg config
	 */
	public function __construct(EventsService $events, Config $config) {
		$this->events = $events;
		$this->config = $config;
	}

	/**
	 * Build a full menu, pulling items from configuration and the "register" menu events.
	 *
	 * Parameters are filtered by the "parameters" event.
	 *
	 * @param string $name   Menu name
	 * @param array  $params Event/view parameters
	 *
	 * @return \Elgg\Menu\Menu
	 */
	public function getMenu(string $name, array $params = []): Menu {
		return $this->prepareMenu($this->getUnpreparedMenu($name, $params));
	}

	/**
	 * Build an unprepared menu.
	 *
	 * @param string $name   Menu name
	 * @param array  $params Event/view parameters
	 *
	 * @return \Elgg\Menu\UnpreparedMenu
	 */
	public function getUnpreparedMenu(string $name, array $params = []): UnpreparedMenu {
		$items = $this->prepareMenuItems(elgg_extract('items', $params, []));
		unset($params['items']);

		$registered_items = elgg_extract($name, $this->menus);
		if (is_array($registered_items)) {
			$items->merge($registered_items);
		}

		$params['name'] = $name;

		$params = $this->events->triggerResults('parameters', "menu:{$name}", $params, $params);

		if (!isset($params['sort_by'])) {
			$params['sort_by'] = 'priority';
		}

		// trigger specific menu events
		$entity = elgg_extract('entity', $params);
		if ($entity instanceof \ElggEntity) {
			$items = $this->events->triggerResults('register', "menu:{$name}:{$entity->type}:{$entity->subtype}", $params, $items);
		}

		$annotation = elgg_extract('annotation', $params);
		if ($annotation instanceof \ElggAnnotation) {
			$items = $this->events->triggerResults('register', "menu:{$name}:{$annotation->getType()}:{$annotation->getSubtype()}", $params, $items);
		}

		$relationship = elgg_extract('relationship', $params);
		if ($relationship instanceof \ElggRelationship) {
			$items = $this->events->triggerResults('register', "menu:{$name}:{$relationship->getType()}:{$relationship->getSubtype()}", $params, $items);
		}

		// trigger generic menu event
		$items = $this->events->triggerResults('register', "menu:{$name}", $params, $items);

		return new UnpreparedMenu($params, $items);
	}

	/**
	 * Split a menu into sections, and pass it through the "prepare" event
	 *
	 * @param UnpreparedMenu $menu Menu
	 *
	 * @return \Elgg\Menu\Menu
	 */
	public function prepareMenu(UnpreparedMenu $menu): Menu {
		$name = $menu->getName();
		$params = $menu->getParams();
		$sort_by = $menu->getSortBy();
		$selected_menu_item_name = elgg_extract('selected_item_name', $params, '');
		
		$builder = new \ElggMenuBuilder($menu->getItems());
		$builder->setSelected($selected_menu_item_name);
		
		$params['menu'] = $builder->getMenu($sort_by);
		$params['selected_item'] = $builder->getSelected();

		// trigger specific menu events
		$entity = elgg_extract('entity', $params);
		if ($entity instanceof \ElggEntity) {
			$params['menu'] = $this->events->triggerResults('prepare', "menu:{$name}:{$entity->type}:{$entity->subtype}", $params, $params['menu']);
		}

		$annotation = elgg_extract('annotation', $params);
		if ($annotation instanceof \ElggAnnotation) {
			$params['menu'] = $this->events->triggerResults('prepare', "menu:{$name}:{$annotation->getType()}:{$annotation->getSubtype()}", $params, $params['menu']);
		}

		$relationship = elgg_extract('relationship', $params);
		if ($relationship instanceof \ElggRelationship) {
			$params['menu'] = $this->events->triggerResults('prepare', "menu:{$name}:{$relationship->getType()}:{$relationship->getSubtype()}", $params, $params['menu']);
		}

		// trigger generic menu event
		$params['menu'] = $this->events->triggerResults('prepare', "menu:$name", $params, $params['menu']);
		
		$params['menu'] = $this->prepareVerticalMenu($params['menu'], $params);
		$params['menu'] = $this->prepareDropdownMenu($params['menu'], $params);
		$params['menu'] = $this->prepareSelectedParents($params['menu'], $params);
		$params['menu'] = $this->prepareItemContentsView($params['menu'], $params);
		
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
	protected function prepareVerticalMenu(PreparedMenu $menu, array $params): PreparedMenu {
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
		
		/* @var $section MenuSection */
		foreach ($menu as $section) {
			/* @var $menu_item \ElggMenuItem */
			foreach ($section as $menu_item) {
				$prepare($menu_item);
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
	protected function prepareSelectedParents(PreparedMenu $menu, array $params): PreparedMenu {
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
	protected function prepareDropdownMenu(PreparedMenu $menu, array $params): PreparedMenu {
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
				'title' => elgg_echo('more'),
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
	 * Set a content view for each menu item based on the default for the menu
	 *
	 * @param PreparedMenu $menu   the current prepared menu
	 * @param array        $params the menu params
	 *
	 * @return \Elgg\Menu\PreparedMenu
	 * @since 4.2
	 */
	protected function prepareItemContentsView(PreparedMenu $menu, array $params): PreparedMenu {
		$item_contents_view = elgg_extract('item_contents_view', $params, 'navigation/menu/elements/item/url');
		
		$prepare = function(\ElggMenuItem $menu_item) use (&$prepare, $item_contents_view) {
			if (!$menu_item->hasItemContentsView()) {
				$menu_item->setItemContentsView($item_contents_view);
			}
			
			foreach ($menu_item->getChildren() as $child_menu_item) {
				$prepare($child_menu_item);
			}
		};
		
		/* @var $section MenuSection */
		foreach ($menu as $section) {
			/* @var $menu_item \ElggMenuItem */
			foreach ($section as $menu_item) {
				$prepare($menu_item);
			}
		}
		
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
	 * @param string   $new_name Combined menu name (used for the prepare event)
	 *
	 * @return \Elgg\Menu\UnpreparedMenu
	 */
	public function combineMenus(array $names = [], array $params = [], string $new_name = ''): UnpreparedMenu {
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
	 * @param array $items An array of \ElggMenuItem instances or menu item factory options
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public function prepareMenuItems(array $items = []): MenuItems {
		$prepared_items = new MenuItems();

		foreach ($items as $item) {
			if (is_array($item)) {
				$options = $item;
				$item = \ElggMenuItem::factory($options);
			}

			if (!$item instanceof \ElggMenuItem) {
				continue;
			}

			$prepared_items->add($item);
		}

		return $prepared_items;
	}
	
	/**
	 * Register a menu item
	 *
	 * @param string $menu_name The name of the menu
	 * @param mixed  $menu_item An \ElggMenuItem object
	 *
	 * @return void
	 * @since 5.0
	 */
	public function registerMenuItem(string $menu_name, \ElggMenuItem $menu_item): void {
		$this->menus[$menu_name][] = $menu_item;
	}
	
	/**
	 * Remove an item from a menu
	 *
	 * @param string $menu_name The name of the menu
	 * @param string $item_name The unique identifier for this menu item
	 *
	 * @return \ElggMenuItem|null
	 * @since 5.0
	 */
	public function unregisterMenuItem(string $menu_name, string $item_name): ?\ElggMenuItem {
		if (!isset($this->menus[$menu_name])) {
			return null;
		}
		
		foreach ($this->menus[$menu_name] as $index => $menu_item) {
			if ($menu_item->getName() === $item_name) {
				$item = $this->menus[$menu_name][$index];
				unset($this->menus[$menu_name][$index]);
				return $item;
			}
		}
		
		return null;
	}
	
	/**
	 * Returns all registered menu items. Used for debugging purpose.
	 *
	 * @return \ElggMenuItem[]
	 * @since 5.0
	 * @internal
	 */
	public function getAllMenus(): array {
		return $this->menus;
	}
}
