<?php
/**
 * Embed landing page
 *
 * @uses string $vars['sections'] Array of section_id => Section Display Name
 * @uses string $vars['active_section'] Currently selected section_id
 */

$sections = (isset($vars['sections'])) ? $vars['sections'] : array();
$active_section = (isset($vars['active_section'])) ? $vars['active_section'] : array_shift(array_keys($sections));

if (!$sections) {
	$content = elgg_echo('embed:no_sections');
} else {
	$offset = max(0, get_input('offset', 0));
	$limit = get_input('limit', 10);

	$content = elgg_view_title(elgg_echo('embed:embed'));

	// prepare tabbed menu
	$tabs = array();
	foreach ($sections as $section_id => $section_info) {
		$tab = array(
			'title' => $section_info['name'],
			'url' => '#',
			'url_class' => 'embed_section',
			// abusing the js attribute.
			'url_js' => "id=\"$section_id\""
		);

		if ($section_id == $active_section) {
			$tab['selected'] = TRUE;
		}
		$tabs[] = $tab;
	}

	$tabs_html = elgg_view('navigation/tabs', array('tabs' => $tabs));
	$content .= $tabs_html;

	$pagination_vars = array(

	);

	$content .= elgg_view('navigation/pagination', $pagination_vars);

	if (array_key_exists($active_section, $sections)) {
		$section_info = $sections[$active_section];
		$layout = isset($section_info['layout']) ? $section_info['layout'] : 'list';

		$params =  array(
			//'type'	=> $type,
			//'subtype'	=> $subtype,
			'offset' => $offset,
			'limit' => $limit,
			'section' => $active_section
		);

		// allow full override for this section
		// check for standard hook
		if ($section_content = elgg_view("embed/$active_section/content", $params)) {
			// handles its own pagination
			$content .= $section_content;
		} elseif ($embed_info = trigger_plugin_hook('embed_get_items', $active_section, $params, array('items' => array(), 'count' => 0))) {
			$params['items'] = $embed_info['items'];
			$params['count'] = $embed_info['count'];
			// pagination here.
			$content .= elgg_view('navigation/pagination', $params);
			$content .= elgg_view("embed/layouts/{$section_info['layout']}", $params);
		} else {
			$content .= elgg_echo('embed:no_section_content');
		}
	} else {
		$content .= elgg_echo('embed:invalid_section');
	}

}
echo $content;
?>

<script type="text/javascript">
$(document).ready(function() {

	// insert embed codes
	$('.embed_section').click(function() {
		var section = $(this).attr('id');
		var url = '<?php echo $vars['url']; ?>pg/embed/embed?active_section=' + section;
		$('#facebox .body .content').load(url);

		return false;
	});

	// handle pagination
	function elggPaginationClick() {
		$('#facebox .body .content').load($(this).attr('href'));
		return false;
	}

	$('.pagination_number').click(elggPaginationClick);
	$('.pagination_next').click(elggPaginationClick);
	$('.pagination_previous').click(elggPaginationClick);
});

</script>

