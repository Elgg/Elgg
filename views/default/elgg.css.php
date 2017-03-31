<?php
/**
 * Elgg primary CSS view
 *
 * @package Elgg.Core
 * @subpackage UI
 */

echo elgg_view('twbs/css/bootstrap.css');

/*******************************************************************************

Skin CSS
 * typography     - fonts, line spacing
 * forms          - forms, inputs
 * buttons        - action, cancel, delete, submit, dropdown, special
 * navigation     - menus, breadcrumbs, pagination
 * icons          - icons, graphics
 * modules        - modules, widgets
 * layout_objects - lists, content blocks, notifications, avatars
 * layout         - page layout
 * misc           - to be removed/redone
 * fa             - hacks to reduce size inconsistencies in Font awesome icons

*******************************************************************************/
echo elgg_view('elements/typography.css', $vars);
echo elgg_view('elements/forms.css', $vars);
echo elgg_view('elements/buttons.css', $vars);
echo elgg_view('elements/icons.css', $vars);
echo elgg_view('elements/navigation.css', $vars);
echo elgg_view('elements/modules.css', $vars);
echo elgg_view('elements/widgets.css', $vars);
echo elgg_view('elements/components.css', $vars);
echo elgg_view('elements/layout.css', $vars);
echo elgg_view('elements/misc.css', $vars);
echo elgg_view('elements/misc/spinner.css', $vars);
echo elgg_view('elements/fa.css', $vars);


// included last to have higher priority
echo elgg_view('elements/helpers.css', $vars);
