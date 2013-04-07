<?php

/**
 * Interface for objects that modify $options for elgg_get_entities/metadata/etc.
 *
 * @access private
 *
 * @package    Elgg.Core
 */
interface Elgg_QueryModifierInterface {

	/**
	 * Get the modified $options array for an elgg_get_*() query
	 *
	 * @return array
	 */
	public function getOptions();
}
