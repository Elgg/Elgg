<?php
/**
 * Primary river item view
 *
 * Calls the individual view saved for that river item. Most of these
 * individual river views then use the views in river/elements.
 *
 * @uses $vars['item'] ElggRiverItem
 */

// @todo remove this in Elgg 1.9
global $_elgg_special_river_catch;
if (!isset($_elgg_special_river_catch)) {
	$_elgg_special_river_catch = false;
}
if ($_elgg_special_river_catch) {
	// we changed the views a little in 1.8.1 so this catches the plugins that
	// were updated in 1.8.0 and redirects to the layout view
	echo elgg_view('river/elements/layout', $vars);
	return true;
}
$_elgg_special_river_catch = true;


$item = $vars['item'];

echo elgg_view($item->getView(), $vars);


$_elgg_special_river_catch = false;
