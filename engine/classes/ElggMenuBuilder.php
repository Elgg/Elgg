<?php

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;

/**
 * Elgg Menu Builder
 *
 * @since 1.8.0
 */
class ElggMenuBuilder {

	/**
	 * @var MenuItems
	 */
	protected $items;

	/**
	 * @var ElggMenuItem
	 */
	protected $selected_item = null;

	/**
	 * \ElggMenuBuilder constructor
	 *
	 * @param ElggMenuItem[]|MenuItems $items Array of \ElggMenuItem objects
	 *
	 * @throws InvalidParameterException
	 */
	public function __construct($items) {
		if (is_array($items)) {
			$items = new MenuItems($items);
		}

		if (!$items instanceof MenuItems) {
			throw new InvalidParameterException(__CLASS__ . ' expects an instanceof of ' . MenuItems::class);
		}

		$this->items = $items;
	}

	/**
	 * Get a prepared menu
	 *
	 * @param mixed $sort_by Method to sort the menu by. @see \ElggMenuBuilder::sort()
	 *
	 * @return PreparedMenu
	 */
	public function getMenu($sort_by = 'priority') {

		$this->selected_item = $this->findSelected();

		return $this->prepare($this->filterByContext(), $sort_by);
	}

	/**
	 * Set a menu item as selected
	 *
	 * @param string $item_name the menu item name to select
	 *
	 * @return bool
	 */
	public function setSelected(string $item_name) {
		$menu_item = $this->items->get($item_name);
		if (!$menu_item instanceof ElggMenuItem) {
			return false;
		}
		
		$menu_item->setSelected();
		
		return true;
	}
	
	/**
	 * Get the selected menu item
	 *
	 * @return ElggMenuItem|null
	 */
	public function getSelected() {
		return $this->selected_item;
	}

	/**
	 * Select menu items for the current context
	 *
	 * @return MenuItems
	 */
	protected function filterByContext() {
		return $this->items->filter(function (ElggMenuItem $item) {
			return $item->inContext();
		});
	}

	/**
	 * Prepare a menu
	 *
	 * @param MenuItems $items   Menu items
	 * @param string    $sort_by Sorting parameter
	 *
	 * @return PreparedMenu
	 */
	protected function prepare(MenuItems $items, $sort_by = 'priority') {
		$menu = $this->setupSections($items);
		$menu = $this->setupTrees($menu);
		$menu = $this->sort($menu, $sort_by);

		return $menu;
	}

	/**
	 * Group the menu items into sections
	 *
	 * @param MenuItems $items Items
	 *
	 * @return PreparedMenu
	 */
	protected function setupSections(MenuItems $items) {
		$menu = new PreparedMenu();

		$section_ids = $items->map(function (ElggMenuItem $item) {
			return $item->getSection();
		});

		$section_ids = array_unique(array_values($section_ids));

		foreach ($section_ids as $index => $section_id) {
			$section_items = $items->filter(function (ElggMenuItem $item) use ($section_id) {
				return $item->getSection() == $section_id;
			});

			$section = new \Elgg\Menu\MenuSection();

			$section->setId($section_id);
			$section->setPriority($index);
			$section->fill($section_items);

			$menu->add($section);
		}

		return $menu;
	}

	/**
	 * Create trees for each menu section
	 *
	 * @param PreparedMenu $menu Prepared menu
	 *
	 * @return PreparedMenu
	 */
	protected function setupTrees(PreparedMenu $menu) {

		return $menu->walk(function (\Elgg\Menu\MenuSection $section) {

			$parents = [];
			$children = [];
			$all_menu_items = [];

			// divide base nodes from children
			foreach ($section as $menu_item) {
				/* @var \ElggMenuItem $menu_item */
				$parent_name = $menu_item->getParentName();
				$menu_item_name = $menu_item->getName();

				if (!$parent_name) {
					// no parents so top level menu items
					$parents[$menu_item_name] = $menu_item;
				} else {
					$children[$menu_item_name] = $menu_item;
				}

				$all_menu_items[$menu_item_name] = $menu_item;
			}

			if (empty($all_menu_items)) {
				// empty sections can be skipped
				return;
			}

			if (empty($parents)) {
				// menu items without parents? That is sad.. report to the log
				$message = _elgg_services()->translator->translate('ElggMenuBuilder:Trees:NoParents');
				_elgg_services()->logger->notice($message);

				// skip section as without parents menu can not be drawn
				return;
			}

			foreach ($children as $menu_item_name => $menu_item) {
				$parent_name = $menu_item->getParentName();

				if (!array_key_exists($parent_name, $all_menu_items)) {
					// orphaned child, inform authorities and skip to next item
					$message = _elgg_services()->translator->translate('ElggMenuBuilder:Trees:OrphanedChild', [
						$menu_item_name,
						$parent_name
					]);
					_elgg_services()->logger->notice($message);

					continue;
				}

				if (!in_array($menu_item, $all_menu_items[$parent_name]->getData('children'))) {
					$all_menu_items[$parent_name]->addChild($menu_item);
					$menu_item->setParent($all_menu_items[$parent_name]);
				} else {
					// menu item already existed in parents children, report the duplicate registration
					$message = _elgg_services()->translator->translate('ElggMenuBuilder:Trees:DuplicateChild', [$menu_item_name]);
					_elgg_services()->logger->notice($message);

					continue;
				}
			}
			// convert keys to indexes for first level of tree
			$parents = array_values($parents);

			$section->fill($parents);
		});
	}

	/**
	 * Find the menu item that is currently selected
	 *
	 * @return ElggMenuItem|null
	 */
	protected function findSelected() {
		foreach ($this->items as $menu_item) {
			if ($menu_item->getSelected()) {
				return $menu_item;
			}
		}
	}

	/**
	 * Sort the menu sections and trees
	 *
	 * @param PreparedMenu $menu    Prepared menu
	 * @param mixed        $sort_by Sort type as string or php callback
	 *
	 * @return PreparedMenu
	 */
	protected function sort(PreparedMenu $menu, $sort_by) {

		$menu->sort(function (\Elgg\Menu\MenuSection $s1, \Elgg\Menu\MenuSection $s2) {
			return strnatcmp($s1->getID(), $s2->getID());
		});

		$sorter = $this->getSortCallback($sort_by);

		if (!$sorter) {
			return $menu;
		}

		return $menu->walk(function (\Elgg\Menu\MenuSection $section) use ($sorter) {
			$indices = array_keys($section->all());

			$section->walk(function (\ElggMenuItem $item) use ($indices, $sorter) {
				$item->setData('original_order', array_search($item->getID(), $indices));
				$item->sortChildren($sorter);
			});

			$section->sort($sorter);
		});
	}

	/**
	 * Get callback function for sorting
	 *
	 * @param string $sort_by Sort name
	 *
	 * @return callable|null
	 */
	protected function getSortCallback($sort_by = null) {
		switch ($sort_by) {
			case 'text':
				return [\ElggMenuBuilder::class, 'compareByText'];

			case 'name':
				return [\ElggMenuBuilder::class, 'compareByName'];

			case 'priority':
				return [\ElggMenuBuilder::class, 'compareByPriority'];
		}

		return $sort_by && is_callable($sort_by) ? $sort_by : null;
	}

	/**
	 * Compare two menu items by their display text
	 * HTML tags are stripped before comparison
	 *
	 * @param ElggMenuItem $a Menu item
	 * @param ElggMenuItem $b Menu item
	 *
	 * @return int
	 */
	public static function compareByText($a, $b) {
		$at = strip_tags($a->getText());
		$bt = strip_tags($b->getText());

		$result = strnatcmp($at, $bt);
		if ($result === 0) {
			return $a->getData('original_order') - $b->getData('original_order');
		}

		return $result;
	}

	/**
	 * Compare two menu items by their identifiers
	 *
	 * @param ElggMenuItem $a Menu item
	 * @param ElggMenuItem $b Menu item
	 *
	 * @return int
	 */
	public static function compareByName($a, $b) {
		$an = $a->getName();
		$bn = $b->getName();

		$result = strnatcmp($an, $bn);
		if ($result === 0) {
			return $a->getData('original_order') - $b->getData('original_order');
		}

		return $result;
	}

	/**
	 * Compare two menu items by their priority
	 *
	 * @param ElggMenuItem $a Menu item
	 * @param ElggMenuItem $b Menu item
	 *
	 * @return int
	 * @since 1.9.0
	 */
	public static function compareByPriority($a, $b) {
		$aw = $a->getPriority();
		$bw = $b->getPriority();

		if ($aw == $bw) {
			return $a->getData('original_order') - $b->getData('original_order');
		}

		return $aw - $bw;
	}
}
