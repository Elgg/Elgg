<?php

namespace Elgg\Menu;

use Elgg\Collections\Collection;
use ElggMenuItem;

/**
 * Represents a menu that has been broken down into sections,
 * with menu hierarchy trees setup
 */
class PreparedMenu extends Collection {

	/**
	 * Get menu section
	 *
	 * @param string $id Section ID
	 *
	 * @return MenuSection
	 */
	public function getSection($id) {
		return $this->get($id);
	}

	/**
	 * Get items in a section
	 *
	 * @param string $section_id Section ID
	 *
	 * @return ElggMenuItem[]
	 */
	public function getItems($section_id) {
		if ($this->has($section_id)) {
			return $this->get($section_id)->all();
		}

		return [];
	}
}
