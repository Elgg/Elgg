<?php
/**
 * Primary river item view
 *
 * Calls the individual view saved for that river item. Most of these
 * individual river views then use the views in river/elements.
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = elgg_extract('item', $vars, false);

if (!$item || !($item instanceof ElggRiverItem)) {
	return true;
}

access_show_hidden_entities(true);
elgg_set_ignore_access();

$error = false;
$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();
$view = $item->getView();

if (!elgg_instanceof($subject)) {
	// subject entity has been deleted or banned
	$error = elgg_echo('river:error:subject');
} else if (!elgg_instanceof($object)) {
	// object entity has been deleted or disabled
	$error = elgg_echo('river:error:object');
} else if (!elgg_view_exists($view, 'default')) {
	// view defined for this river item does not exists
	// checking default viewtype since some viewtypes do not have unique views per item (rss)
	$error = elgg_echo('river:error:view', array($view));
}

if (!empty($error)) {
	echo elgg_view('river/error', array(
		'item' => $item,
		'error' => $error
	));
	return true;
}

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

echo elgg_view($item->getView(), $vars);

$_elgg_special_river_catch = false;
