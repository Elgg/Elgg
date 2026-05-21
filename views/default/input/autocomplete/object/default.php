<?php
/**
 * Default object HTML view for autocomplete items
 *
 * @uses $vars['entity'] the selected entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggObject) {
	return;
}

echo elgg_view('input/autocomplete/default', $vars);
