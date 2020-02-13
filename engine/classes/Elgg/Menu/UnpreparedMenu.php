<?php

namespace Elgg\Menu;

use Elgg\Exceptions\InvalidArgumentException;
use ElggMenuItem;

/**
 * Linear set of menu items collected from configuration and the "register" hook.
 *
 * This also encapsulates parameters to be passed to hooks and views.
 */
class UnpreparedMenu {

	/**
	 * @var MenuItems
	 */
	private $items;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * Constructor
	 *
	 * @param array                    $params Parameters to be passed to the "prepare" hook and views.
	 *                                         Must include value for "name".
	 * @param ElggMenuItem[]|MenuItems $items  Menu items
	 */
	public function __construct(array $params, $items) {
		$this->params = $params;

		if (is_array($items)) {
			$items = new MenuItems($items);
		}

		if (!$items instanceof MenuItems) {
			throw new InvalidArgumentException("Items collection must implement " . MenuItems::class);
		}
		$this->items = $items;
	}

	/**
	 * Set how this menu should be sorted
	 *
	 * @see \ElggMenuBuilder::sort()
	 *
	 * @param string|callable $sort_by Sort strategy "text", "name", "priority", or callback
	 *
	 * @return void
	 */
	public function setSortBy($sort_by = 'text') {
		$this->params['sort_by'] = $sort_by;
	}

	/**
	 * Get the designated (or default) sort strategy
	 *
	 * @see self::setSortBy()
	 * @see \ElggMenuBuilder::sort()
	 *
	 * @return string|callable
	 */
	public function getSortBy() {
		return elgg_extract('sort_by', $this->params, 'priority');
	}

	/**
	 * Get the menu name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->params['name'];
	}

	/**
	 * Get the menu items
	 *
	 * @return MenuItems
	 */
	public function getItems() {
		return $this->items;
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
