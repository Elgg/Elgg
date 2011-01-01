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
 * spacing
 * grid

*******************************************************************************/
echo elgg_view('css/elements/reset', $vars);
echo elgg_view('css/elements/spacing', $vars);
echo elgg_view('css/elements/grid', $vars);
echo elgg_view('css/elements/base', $vars);


/*******************************************************************************
 
 Skin CSS
 * typography - fonts, line spacing
 * chrome - general skin
 * forms - form elements, buttons
 * navigation - menus, breadcrumbs, pagination
 * core - modules, lists, content blocks, notifications, avatars, widgets
 * icons - icons, sprites, graphics
 * layout - page layout
 * misc - river, login, settings, profile
 
*******************************************************************************/
echo elgg_view('css/elements/typography', $vars);
echo elgg_view('css/elements/chrome', $vars);
echo elgg_view('css/elements/forms', $vars);
echo elgg_view('css/elements/navigation', $vars);
echo elgg_view('css/elements/core', $vars);
echo elgg_view('css/elements/icons', $vars);
echo elgg_view('css/elements/layout', $vars);
echo elgg_view('css/elements/misc', $vars);


// in case plugins are still extending the old 'css' view, display it
echo elgg_view('css', $vars);
