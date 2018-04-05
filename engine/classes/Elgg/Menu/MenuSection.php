<?php

namespace Elgg\Menu;

use Elgg\Collections\Collection;
use Elgg\Collections\CollectionItemInterface;
use ElggMenuItem;

/**
 * Menu section
 */
class MenuSection
	extends MenuItems
	implements CollectionItemInterface {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var int
	 */
	protected $priority;

	/**
	 * Set ID
	 *
	 * @param string $id ID
	 *
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Get unique item identifier within a collection
	 * @return string|int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set section priority
	 *
	 * @param int $priority Priority
	 *
	 * @return void
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Get priority (weight) of the item within a collection
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Get menu item by name
	 *
	 * @param string $item_name Menu item name
	 *
	 * @return ElggMenuItem|null
	 */
	public function getItem($item_name) {
		return $this->get($item_name);
	}

	/**
	 * Get menu items
	 * @return ElggMenuItem[]
	 */
	public function getItems() {
		return $this->all();
	}
}