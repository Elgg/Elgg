<?php
/**
* Display an icon from the FontAwesome library.
*
* @uses $vars['class']   Class of elgg-icon
* @uses $vars['convert'] Convert legacy Elgg sprite class to a FontAwesome class (default: true)
*/

$class = elgg_extract_class($vars, ['elgg-icon']);

$convert = elgg_extract('convert', $vars, true);
unset($vars['convert']);

$vars['class'] = _elgg_map_icon_glyph_class($class, $convert);

echo elgg_format_element('span', $vars, '');
