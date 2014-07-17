<?php

namespace Elgg\Views;

/**
 * Stores locations of views by view name and viewtype as a table.
 * 
 * Row keys are view names.
 * Column keys are viewtypes.
 * 
 * @since 2.0.0
 * @access private
 */
interface PathRegistry {
	/**
	 * Returns all known locations in this registry for the given view.
	 * 
	 * @param string $name The name of the view
	 * 
	 * @return Map<Viewtype,?File>
	 */
	public function forView(/*string*/ $name);
	
	/**
	 * Returns a list of all viewtypes configured in this registry.
	 * 
	 * @return Collection<Viewtype>
	 */
	public function getViewtypes();
}
