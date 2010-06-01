<?php
/**
 * Embed - List items
 *
 * @todo Yes this is a lot of logic for a view.  The Javascript is faily complicated
 * and pulling entity views out was making it worse.
 * Once plugin deps are working, we'll remove the logic for that.
 *
 * @uses array $vars['items'] Array of ElggEntities
 * @uses string $vars['section'] The section_id.
 *
 */

$items = isset($vars['items']) ? $vars['items'] : array();
$section = $vars['section'];

// pull out some common tests
// embed requires ECML, but until we have plugin deps working
// we need to explicitly check and use a fallback.
if ($ecml_enabled = is_plugin_enabled('ecml')){
	$ecml_valid_keyword = ecml_is_valid_keyword($section);
} else {
	$ecml_valid_keyword = FALSE;
}

// check if we have an override for this section type.
$view = "embed/$section/item/list";
if (!elgg_view_exists($view)) {
	$view = "embed/item/list";
}

$content = '';
foreach ($items as $item) {
	// sanity checking
	if (!elgg_instanceof($item)) {
		continue;
	}

	$params = array(
		'section' => $section,
		'item' => $item,
		'ecml_enabled' => $ecml_enabled,
		'ecml_keyword' => ($ecml_valid_keyword) ? $section : 'entity'
	);

	$content .= elgg_view($view, $params);
}

echo $content;

?>

<script type="text/javascript">
$(document).ready(function() {
	$('.embed_data').click(function() {
		var embed_code = $(this).data('embed_code');
		elggEmbedInsertContent(embed_code);
	});
});
</script>