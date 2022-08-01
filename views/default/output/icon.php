<?php
/**
* Display an icon from the FontAwesome library.
*
* @uses $vars['class'] Class of elgg-icon
*/

$vars['class'] = elgg_extract_class($vars, ['elgg-icon']);

echo elgg_format_element('span', $vars);
