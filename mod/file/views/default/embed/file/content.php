<?php
/**
 * List files available for upload
 */

$active_section = elgg_extract('active_section', $vars);

$options = array(
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'type_subtype_pair' => array('object' => 'file'),
	'count' => true
);

$count = elgg_get_entities($options);

if ($count) {
	echo "<div class='embed_modal_$active_section'>";

	unset($options['count']);
	$items = elgg_get_entities($options);

	foreach ($items as $item) {

		// different entity types have different title attribute names.
		$title = isset($item->name) ? $item->name : $item->title;
		// don't let it be too long
		$title = elgg_get_excerpt($title);

		$author_text = elgg_echo('byline', array($owner->name));
		$date = elgg_view_friendly_time($item->time_created);

		$subtitle = "$author_text $date";

		$icon = "<img src=\"{$item->getIconURL($icon_size)}\" />" . htmlentities($title, ENT_QUOTES, 'UTF-8');

		$embed_code = elgg_view('output/url', array(
			'href' => $item->getURL(),
			'title' => $title,
			'text' => $icon,
			'encode_text' => FALSE
		));

		$item_icon = elgg_view_entity_icon($item, $icon_size);

		$params = array(
			'title' => $title,
			'entity' => $item,
			'subtitle' => $subtitle,
			'tags' => FALSE,
		);
		$list_body = elgg_view('object/elements/summary', $params);

		// @todo JS 1.8: is this approach better than inline js?
		echo "<div class=\"embed_data\" id=\"embed_{$item->getGUID()}\">" . elgg_view_image_block($item_icon, $list_body) . '</div>';
		echo "<script type=\"text/javascript\">
			$('#embed_{$item->getGUID()}').data('embed_code', " . json_encode($embed_code) . ");
		</script>";
	}

	echo '</div>';
}