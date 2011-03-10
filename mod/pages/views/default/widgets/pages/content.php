<?php
/**
 * Elgg pages widget
 *
 * @package ElggPages
 */

$num = (int) $vars['entity']->pages_num;

$options = array(
	'type' => 'object',
	'subtype' => 'page_top',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$url = "pages/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('pages:more'),
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('pages:none');
}
