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
$active_section = elgg_extract('active_section', $vars, array_shift(array_keys($sections)));
$upload_sections = elgg_extract('upload_sections', $vars, array());
$internal_id = elgg_extract('internal_id', $vars);

if (!$sections) {
	$content = elgg_echo('embed:no_sections');
} else {
	$content = elgg_view_title(elgg_echo('embed:media'));
	$content .= elgg_view('embed/tabs', $vars);

	$offset = max(0, get_input('offset', 0));
	$limit = get_input('limit', 10);

	// build the items and layout.
	if ($active_section == 'upload' || array_key_exists($active_section, $sections)) {
		$section_info = $sections[$active_section];
		$layout = isset($section_info['layout']) ? $section_info['layout'] : 'list';

		$params =  array(
			'offset' => $offset,
			'limit' => $limit,
			'section' => $active_section,
			'upload_sections' => $upload_sections,
			'internal_id' => $internal_id
		);

		// allow full override for this section
		// check for standard hook
		if ($section_content = elgg_view("embed/$active_section/content", $params)) {
			// handles its own pagination
			$content .= $section_content;
		} else {
			// see if anyone has any items to display for the active section
			$result = array('items' => array(), 'count' => 0);
			$embed_info = elgg_trigger_plugin_hook('embed_get_items', $active_section, $params, $result);

			// do we use default view or has someone defined "embed/$active_section/item/$layout"
			$view = "embed/$active_section/item/$layout";
			if (!elgg_view_exists($view)) {
				$view = "embed/item/$layout";
			}
			
			if (!isset($embed_info['items']) || !is_array($embed_info['items']) || !count($embed_info['items'])) {
				$content .= elgg_echo('embed:no_section_content');
			} else {
				elgg_push_context('widgets');
				$content .= elgg_view_entity_list($embed_info['items'], array(
					'full_view' => false,
				));
				elgg_pop_context();

				$js = elgg_view('js/embed/inline', array(
					'items' => $embed_info['items'],
				));
			}
		}
	} else {
		$content .= elgg_echo('embed:invalid_section');
	}
}

echo '<div class="embed-wrapper">' . $content . '</div>';

if (isset($js)) {
	echo '<script type="text/javascript">';
	echo $js;
	echo '</script>';
}
