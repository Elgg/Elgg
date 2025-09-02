<?php
/**
 * View a wire post
 *
 * @uses $vars['entity'] ElggWire to show
 *
 * @deprecated 6.3 use "object/thewire/full" or "object/thewire/summary"
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

elgg_deprecated_notice('The view "object/thewire" has been deprecated, use "object/thewire/full" or "object/thewire/summary"', '6.3');

if (elgg_extract('full_view', $vars)) {
	echo elgg_view('object/thewire/full', $vars);
} else {
	echo elgg_view('object/thewire/summary', $vars);
}
