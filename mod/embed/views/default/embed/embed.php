<?php
/**
 * Embed landing page
 *
 * @todo Yes this is a lot of logic for a view.  A good bit of it can be moved
 * to the page handler
 *
 * @uses string $vars['sections'] Array of section_id => Section Display Name
 * @uses string $vars['active_section'] Currently selected section_id
 */

$sections = elgg_extract('sections', $vars, array());
$active_section = elgg_extract('active_section', $vars, array_shift(array_keys($sections)), false);
$upload_sections = elgg_extract('upload_sections', $vars, array());

if (!$sections) {
	$content = elgg_echo('embed:no_sections');
} else {
	$content = elgg_view_title(elgg_echo('embed:media'));
	$content .= elgg_view('embed/tabs', $vars);

	$offset = (int)max(0, get_input('offset', 0));
	$limit = (int)get_input('limit', 5);

	// find the view to display
	// @todo make it so you don't have to manually create views for each page
	$view = "embed/$active_section/content";
	
	$section_content = elgg_view($view, $vars);

	// build the items and layout.
	if ($section_content) {
		$content .= $section_content;
	} else {
		$content .= elgg_echo('embed:no_section_content');
	}
}

echo '<div class="embed-wrapper">' . $content . '</div>';

if (isset($js)) {
	echo '<script type="text/javascript">';
	echo $js;
	echo '</script>';
}
