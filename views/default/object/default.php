<?php
/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */

$full_view = elgg_extract('full_view', $vars, false);

$view = $full_view ? 'object/elements/full' : 'object/elements/summary';

echo elgg_view($view, $vars);