<?php

/**
 * Displays a meta block for the entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

elgg_push_context('meta_block');

echo elgg_view('page/components/module', [
	'header' => elgg_view('page/elements/meta_block/header', $vars),
	'body' => elgg_view('page/elements/meta_block/body', $vars),
	'footer' => elgg_view('page/elements/meta_block/footer', $vars),
	'class' => 'elgg-meta-block',
]);

elgg_pop_context();
