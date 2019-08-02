<?php

namespace Elgg\Menu;

use Elgg\Collections\Collection;

/**
 * A collection of menu items
 */
class MenuItems extends Collection {

	/**
	 * {@inheritdoc}
	 */
	public function __construct($items = [], $item_class = \ElggMenuItem::class) {
		parent::__construct($items, $item_class);
	}
}
