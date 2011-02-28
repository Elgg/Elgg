<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */



// Elgg logo
echo elgg_view_menu('topbar', array('sort_by' => 'weight'));

// elgg tools menu
// need to echo this empty view for backward compatibility.
// @todo -- do we really?  So much else is broken, and the new menu system is so much nicer...
echo elgg_view("navigation/topbar_tools");
