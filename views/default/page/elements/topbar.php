<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */



// Elgg logo
$image = '<img src="' . elgg_get_site_url() . '_graphics/elgg_toolbar_logo.gif" alt="Elgg logo" />';
echo elgg_view('output/url', array(
	'href' => 'http://www.elgg.org/',
	'text' => $image,
));

echo elgg_view_menu('topbar', array('sort_by' => 'weight'));

echo elgg_view_menu('topbar_alt', array('sort_by' => 'weight'));

// elgg tools menu
// need to echo this empty view for backward compatibility.
// @todo -- do we really?  So much else is broken, and the new menu system is so much nicer...
echo elgg_view("navigation/topbar_tools");
