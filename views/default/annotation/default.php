<?php
/**
 * Elgg default annotation view
 *
 * @note To add or remove from the annotation menu, register handlers for the menu:annotation hook.
 *
 * @uses $vars['annotation'] ElggAnnotation
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

echo elgg_view('annotation/elements/summary', $vars);
