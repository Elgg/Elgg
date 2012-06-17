<?php
/**
 * Layout of embed panel loaded in lightbox
 */

$title =  elgg_view_title(elgg_echo('embed:media'));

$menu = elgg_view_menu('embed');

$selected = elgg_get_config('embed_tab');
if ($selected->getData('view')) {
	$tab = elgg_view($selected->getData('view'), $vars);
} else {
	$tab = elgg_list_entities(
			embed_get_list_options($selected->getData('options')),
			'elgg_get_entities',
			'embed_list_items'
			);
	if (!$tab) {
		$tab = elgg_echo('embed:no_section_content');
	}
}

$tab .= elgg_view('graphics/ajax_loader', array(
	'class' => 'embed-throbber mtl',
));

$container_info = elgg_view('input/hidden', array(
	'name' => 'embed_container_guid',
	'value' => elgg_get_page_owner_guid(),
));

echo <<<HTML
<div class="embed-wrapper">
	$title
	$menu
	$tab
	$container_info
</div>
HTML;
