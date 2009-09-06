<?php
	/**
	 * ElggSite default view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	// sites information (including plugin settings) shouldn't be shown.
	// there's not a real reason to display a site object
	// unless specifically overriden with a subtype view.
	if ($site = $vars['entity']->url) {
		forward($site);
	} else {
		forward();
	}

	//echo elgg_view('object/default', $vars);
?>