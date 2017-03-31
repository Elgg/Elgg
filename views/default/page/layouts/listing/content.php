<?php

/**
 * Displays the content of the listing layout
 *
 * @uses $vars['type']           Listing type
 * @uses $vars['identifier']     Route identifier
 * @uses $vars['target']         Target entity
 * @uses $vars['entity_type']    Listed entity type
 * @uses $vars['entity_subtype'] Listed entity subtype
 */

$listing_type = elgg_extract('type', $vars);
$identifier = elgg_extract('identifier', $vars);

$entity_type = elgg_extract('entity_type', $vars);
$entity_subtype = elgg_extract('entity_subtype', $vars);

$views = [
	"listing/$identifier/$listing_type",
	"listing/$entity_type/$entity_subtype/$listing_type",
	"listing/$entity_type/$entity_subtype",
	"listing/default",
];

foreach ($views as $view) {
	if (elgg_view_exists($view)) {
		$params = $vars;
		$params['sort_by'] = 'priority';
		$output = elgg_view_menu('listing', $params);
		$output .= elgg_view($view, $vars);
		echo elgg_format_element('div', [
			'class' => 'elgg-listing',
		], $output);
		return;
	}
}

elgg_log("Listing parameters can not be used to render the listing. Please add a view in '$listing_view'", 'ERROR');