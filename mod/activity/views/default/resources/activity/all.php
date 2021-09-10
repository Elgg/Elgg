<?php
/**
 * Show all site river activity
 */

// get filter options
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

// build page content
$content = elgg_view('river/listing/all', [
	'entity_type' => $type,
	'entity_subtype' => $subtype,
	'show_filter' => true,
]);

// draw page
echo elgg_view_page(elgg_echo('river:all'), [
	'content' =>  $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => 'all',
	'class' => 'elgg-river-layout',
	
	// set type/subtype to trick filter menu hook to consistently generate tabs (needed because of index resource)
	'entity_type' => 'river',
	'entity_subtype' => 'river',
]);
