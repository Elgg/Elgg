<?php
/**
 * Elgg primary CSS view
 *
 * @package Elgg.Core
 * @subpackage UI
 */

// check if there is a theme overriding the old css view and use it, if it exists
$old_css_view = elgg_get_view_location('css');
if ($old_css_view != "{$CONFIG->viewpath}") {
	echo elgg_view('css', $vars);
	return true;
}


/*******************************************************************************

Base CSS
 * CSS reset
 * helpers
 * grid

*******************************************************************************/
echo elgg_view('css/elements/reset', $vars);
echo elgg_view('css/elements/helpers', $vars);
echo elgg_view('css/elements/grid', $vars);


/*******************************************************************************

Skin CSS
 * typography     - fonts, line spacing
 * chrome         - general skin
 * forms          - form elements, buttons
 * navigation     - menus, breadcrumbs, pagination
 * icons          - icons, sprites, graphics
 * modules        - modules, widgets
 * layout_objects - lists, content blocks, notifications, avatars
 * page_layout    - page layout
 * misc           - to be removed/redone

*******************************************************************************/
echo elgg_view('css/elements/typography', $vars);
echo elgg_view('css/elements/chrome', $vars);
echo elgg_view('css/elements/forms', $vars);
echo elgg_view('css/elements/icons', $vars);
echo elgg_view('css/elements/navigation', $vars);
echo elgg_view('css/elements/modules', $vars);
echo elgg_view('css/elements/layout_objects', $vars);
echo elgg_view('css/elements/page_layout', $vars);
echo elgg_view('css/elements/misc', $vars);


// in case plugins are still extending the old 'css' view, display it
echo elgg_view('css', $vars);
