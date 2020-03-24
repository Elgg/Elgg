<?php
/**
* Display an icon from the FontAwesome library.
*
* @uses $vars['class']   Class of elgg-icon
*/

$class = elgg_extract_class($vars, ['elgg-icon']);

$vars['class'] = _elgg_map_icon_glyph_class($class);

echo elgg_format_element('span', $vars);
