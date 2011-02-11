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
$ecml_keyword = (isset($vars['ecml_enabled']) && isset($vars['ecml_keyword'])) ? $vars['ecml_keyword'] : NULL;
$icon_size = $vars['icon_size'];

// @todo add entity checking.

// different entity types have different title attribute names.
$title = isset($item->name) ? $item->name : $item->title;
// don't let it be too long
$title = elgg_get_excerpt($title);

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
		'text' => $title,
		'encode_text' => FALSE
	));
}

$icon = "<img src=\"{$item->getIcon($icon_size)}\" />";
$info = "<p class='entity-title'>" . htmlentities($title, ENT_QUOTES, 'UTF-8') . "</p>";
$info .= "<p class='entity-subtext'>" . elgg_view_friendly_time($vars['item']->time_created) . "</p>";

// @todo JS 1.8: is this approach better than inline js?
echo "<div class=\"embed_data\" id=\"embed_{$item->getGUID()}\">" . elgg_view_listing($icon, $info) . '</div>';
echo "<script type=\"text/javascript\">
	$('#embed_{$item->getGUID()}').data('embed_code', " . json_encode($embed_code) . ");
</script>";