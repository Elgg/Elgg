<?php

namespace Elgg\Mocks;

use Elgg\Project\Paths;

/**
 * Plugin class used during tests
 */
class ElggPlugin extends \ElggPlugin {

	/**
	 * Returns the plugin's full path with trailing slash.
	 *
	 * @return string
	 */
	public function getPath() {
		// testing plugins can come from custom locations. If folder does not exist fallback to project mod folder
		if (!is_dir(parent::getPath())) {
			$alt_path = Paths::project() . 'mod/' . $this->getID();
			if (is_dir($alt_path)) {
				$this->setPath($alt_path);
			}
		}
		
		return $this->path;
	}
}
