<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */



// Elgg logo
echo elgg_view_menu('topbar', array('sort_by' => 'priority', array('elgg-menu-hz')));

// elgg tools menu
// need to echo this empty view for backward compatibility.
echo elgg_view("navigation/topbar_tools");
