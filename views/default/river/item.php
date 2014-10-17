<?php
/**
 * Primary river item view
 *
 * Calls the individual view saved for that river item. Most of these
 * individual river views then use the views in river/elements.
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

echo elgg_view($item->getView(), $vars);