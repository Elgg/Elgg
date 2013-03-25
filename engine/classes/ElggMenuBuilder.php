<?php
/**
 * Elgg Menu Builder
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 * @since      1.8.0
 */
class ElggMenuBuilder {

	/**
	 * @var ElggMenuItem[]
	 */
	protected $menu = array();

	protected $selected = null;

	/**
	 * ElggMenuBuilder constructor
	 *
	 * @param ElggMenuItem[] $menu Array of ElggMenuItem objects
	 */
	public function __construct(array $menu) {
		$this->menu = $menu;
	}

	/**
	 * Get a prepared menu array
	 *
	 * @param mixed $sort_by Method to sort the menu by. @see ElggMenuBuilder::sort()
	 * @return array
	 */
	public function getMenu($sort_by = 'text') {

		$this->selectFromContext();

		$this->selected = $this->findSelected();

		$this->setupSections();

		$this->setupTrees();

		$this->sort($sort_by);

		return $this->menu;
	}

	/**
	 * Get the selected menu item
	 *
	 * @return ElggMenuItem
	 */
	public function getSelected() {
		return $this->selected;
	}

	/**
	 * Select menu items for the current context
	 *
	 * @return void
	 */
	protected function selectFromContext() {
		if (!isset($this->menu)) {
			$this->menu = array();
			return;
		}

		// get menu items for this context
		$selected_menu = array();
		foreach ($this->menu as $menu_item) {
			if (!is_object($menu_item)) {
				elgg_log("A non-object was passed to ElggMenuBuilder", "ERROR");
				continue;
			}
			if ($menu_item->inContext()) {
				$selected_menu[] = $menu_item;
			}
		}

		$this->menu = $selected_menu;
	}

	/**
	 * Group the menu items into sections
	 * 
	 * @return void
	 */
	protected function setupSections() {
		$sectioned_menu = array();
		foreach ($this->menu as $menu_item) {
			if (!isset($sectioned_menu[$menu_item->getSection()])) {
				$sectioned_menu[$menu_item->getSection()] = array();
			}
			$sectioned_menu[$menu_item->getSection()][] = $menu_item;
		}
		$this->menu = $sectioned_menu;
	}

	/**
	 * Create trees for each menu section
	 *
	 * @internal The tree is doubly linked (parent and children links)
	 * @return void
	 */
	protected function setupTrees() {
		$menu_tree = array();

		foreach ($this->menu as $key => $section) {
			$parents = array();
			$children = array();
			// divide base nodes from children
			foreach ($section as $menu_item) {
				/* @var ElggMenuItem $menu_item */
				$parent_name = $menu_item->getParentName();
				if (!$parent_name) {
					$parents[$menu_item->getName()] = $menu_item;
				} else {
					$children[] = $menu_item;
				}
			}

			// attach children to parents
			$iteration = 0;
			$current_gen = $parents;
			$next_gen = null;
			while (count($children) && $iteration < 5) {
				foreach ($children as $index => $menu_item) {
					$parent_name = $menu_item->getParentName();
					if (array_key_exists($parent_name, $current_gen)) {
						$next_gen[$menu_item->getName()] = $menu_item;
						$current_gen[$parent_name]->addChild($menu_item);
						$menu_item->setParent($current_gen[$parent_name]);
						unset($children[$index]);
					}
				}
				$current_gen = $next_gen;
				$iteration += 1;
			}

			// convert keys to indexes for first level of tree
			$parents = array_values($parents);

			$menu_tree[$key] = $parents;
		}

		$this->menu = $menu_tree;
	}

	/**
	 * Find the menu item that is currently selected
	 *
	 * @return ElggMenuItem
	 */
	protected function findSelected() {

		// do we have a selected menu item already
		foreach ($this->menu as $menu_item) {
			if ($menu_item->getSelected()) {
				return $menu_item;
			}
		}

		// scan looking for a selected item
		foreach ($this->menu as $menu_item) {
			if ($menu_item->getHref()) {
				if (elgg_http_url_is_identical(full_url(), $menu_item->getHref())) {
					$menu_item->setSelected(true);
					return $menu_item;
				}
			}
		}

		return null;
	}

	/**
	 * Sort the menu sections and trees
	 *
	 * @param mixed $sort_by Sort type as string or php callback
	 * @return void
	 */
	protected function sort($sort_by) {

		// sort sections
		ksort($this->menu);

		switch ($sort_by) {
			case 'text':
				$sort_callback = array('ElggMenuBuilder', 'compareByText');
				break;
			case 'name':
				$sort_callback = array('ElggMenuBuilder', 'compareByName');
				break;
			case 'priority':
				$sort_callback = array('ElggMenuBuilder', 'compareByWeight');
				break;
			case 'register':
				// use registration order - usort breaks this
				return;
				break;
			default:
				if (is_callable($sort_by)) {
					$sort_callback = $sort_by;
				} else {
					return;
				}
				break;
		}

		// sort each section
		foreach ($this->menu as $index => $section) {
			foreach ($section as $key => $node) {
				$section[$key]->setData('original_order', $key);
			}
			usort($section, $sort_callback);
			$this->menu[$index] = $section;

			// depth first traversal of tree
			foreach ($section as $root) {
				$stack = array();
				array_push($stack, $root);
				while (!empty($stack)) {
					$node = array_pop($stack);
					/* @var ElggMenuItem $node */
					$node->sortChildren($sort_callback);
					$children = $node->getChildren();
					if ($children) {
						$stack = array_merge($stack, $children);
					}
				}
			}
		}
	}

	/**
	 * Compare two menu items by their display text
	 *
	 * @param ElggMenuItem $a Menu item
	 * @param ElggMenuItem $b Menu item
	 * @return bool
	 */
	public static function compareByText($a, $b) {
		$at = $a->getText();
		$bt = $b->getText();

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
	 * @return bool
	 */
	public static function compareByName($a, $b) {
		$an = $a->getName();
		$bn = $b->getName();

		$result = strcmp($an, $bn);
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
	 * @return bool
	 *
	 * @todo change name to compareByPriority
	 */
	public static function compareByWeight($a, $b) {
		$aw = $a->getWeight();
		$bw = $b->getWeight();

		if ($aw == $bw) {
			return $a->getData('original_order') - $b->getData('original_order');
		}
		return $aw - $bw;
	}
}
