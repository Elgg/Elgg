<?php
/**
 * Default item view for embed items in list display.
 *
 * Why don't we recycle the view/type/subtype views?
 * Because we need to have special JavaScript that fires when you click on
 * the icon / title.
 *
 * @uses object $vars['item'] The item to display
 * @return string A formatted item
 */

$item = $vars['item'];
$section = $vars['section'];
$target = $vars['target'];
$ecml_keyword = (isset($vars['ecml_enabled']) && $vars['ecml_enabled'] && isset($vars['ecml_keyword'])) ? $vars['ecml_keyword'] : NULL;
$icon_size = $vars['icon_size'];
$owner = $item->getOwnerEntity();

// @todo add entity checking.

// different entity types have different title attribute names.
$title = isset($item->name) ? $item->name : $item->title;
// don't let it be too long
$title = elgg_get_excerpt($title);

$author_text = elgg_echo('byline', array($owner->name));
$date = elgg_view_friendly_time($item->time_created);

$subtitle = "$author_text $date";

// @todo you can disable plugins that are required by other plugins
// (embed requires ecml) so fallback to a hard-coded check to see if ecml is enabled.
// #grumble
if ($ecml_keyword) {
	$embed_code = "[$ecml_keyword guid={$item->getGUID()}]";
} else {
	// fallback to inserting a hard link to the object with its icon
	$icon = "<img src=\"{$item->getIcon($icon_size)}\" />" . htmlentities($title, ENT_QUOTES, 'UTF-8');

	$embed_code = elgg_view('output/url', array(
		'href' => $item->getURL(),
		'title' => $title,
		'text' => $icon,
		'encode_text' => FALSE
	));
}

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