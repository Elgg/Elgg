<?php
namespace Elgg\Menu;

use ElggMenuItem;

/**
 * Linear set of menu items collected from configuration and the "register" hook.
 *
 * This also encapsulates parameters to be passed to hooks and views.
 */
class UnpreparedMenu {

	/**
	 * @var ElggMenuItem[]
	 */
	private $items;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * Constructor
	 *
	 * @param array          $params Parameters to be passed to the "prepare" hook and views.
	 *                               Must include value for "name".
	 * @param ElggMenuItem[] $items  Menu items
	 *
	 * @access private
	 * @internal Do not use. Use the `elgg()->menus` service methods instead.
	 */
	public function __construct(array $params, array $items) {
		$this->params = $params;
		$this->items = $items;
	}

	/**
	 * Set how this menu should be sorted
	 *
	 * @see ElggMenuBuilder::sort
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
	 * @see setSortBy
	 * @see ElggMenuBuilder::sort
	 *
	 * @return string|callable
	 */
	public function getSortBy() {
		return elgg_extract('sort_by', $this->params, 'text');
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
	 * @return ElggMenuItem[]
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
