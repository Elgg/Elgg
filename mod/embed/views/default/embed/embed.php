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
	$offset = max(0, get_input('offset', 0));
	$limit = get_input('limit', 10);

	$content = elgg_view_title(elgg_echo('embed:media'));
	//$content .= elgg_echo('embed:instructions');

	// prepare tabbed menu
	$tabs = array();
	foreach ($sections as $section_id => $section_info) {
		$tab = array(
			'title' => $section_info['name'],
			'url' => '#',
			'url_class' => 'embed_section',
			'url_id' => $section_id,
		);

		if ($section_id == $active_section) {
			$tab['selected'] = TRUE;
		}
		$tabs[] = $tab;
	}

	// make sure upload is always the last tab
	if ($upload_sections) {
		$tabs[] = array(
			'title' => elgg_echo('embed:upload'),
			'url' => '#',
			'url_class' => 'embed_section',
			'url_id' => 'upload',
			'selected' => ($active_section == 'upload')
		);
	}

	$tabs_html = elgg_view('navigation/tabs', array('tabs' => $tabs));
	$content .= $tabs_html;

	// build the items and layout.
	if ($active_section == 'upload' || array_key_exists($active_section, $sections)) {
		$section_info = $sections[$active_section];
		$layout = isset($section_info['layout']) ? $section_info['layout'] : 'list';

		$params =  array(
			//'type'	=> $type,
			//'subtype'	=> $subtype,
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
		} elseif ($embed_info = elgg_trigger_plugin_hook('embed_get_items', $active_section, $params, array('items' => array(), 'count' => 0))) {
			// check if we have an override for this section type.
			$view = "embed/$active_section/item/$layout";

			if (!elgg_view_exists($view)) {
				$view = "embed/item/$layout";
			}
			
			if (!isset($embed_info['items']) || !is_array($embed_info['items']) || !count($embed_info['items'])) {
				$content .= elgg_echo('embed:no_section_content');
			} else {
				// pull out some common tests
				// embed requires ECML, but until we have plugin deps working
				// we need to explicitly check and use a fallback.
				if ($ecml_enabled = elgg_is_active_plugin('ecml')){
					$ecml_valid_keyword = ecml_is_valid_keyword($active_section);
				} else {
					$ecml_valid_keyword = FALSE;
				}
				
				$items_content = '<ul class="elgg-list">';
				foreach ($embed_info['items'] as $item) {
					$item_params = array(
						'section' => $active_section,
						'item' => $item,
						'ecml_enabled' => $ecml_enabled,
						'ecml_keyword' => ($ecml_valid_keyword) ? $active_section : 'entity',
						'icon_size' => elgg_extract('icon_size', $section_info, 'tiny'),
					);
	
					$items_content .= '<li class="elgg-list-item">' . elgg_view($view, $item_params) . '</li>';
				}
				$items_content .= '</ul>';
				
				$params['content'] = $items_content;
				$params['count'] = $embed_info['count'];
	
				$content .= elgg_view('navigation/pagination', $params);
				$content .= elgg_view("embed/layouts/$layout", $params);
			}
		} else {
			$content .= elgg_echo('embed:no_section_content');
		}
	} else {
		$content .= elgg_echo('embed:invalid_section');
	}
}

echo $content;
?>

<?php //@todo: JS 1.8: ugly ?>
<script type="text/javascript">
$(function() {
	var internal_id = '<?php echo addslashes($internal_id); ?>';

	// Remove any existing "live" handlers
	$('.embed_data').die('click');
	$('.embed_section').die('click');
	$('#facebox .elgg-pagination a').die('click');
	
	// insert embed codes
	$('.embed_data').live('click', function() {
		var embed_code = $(this).data('embed_code');
		elggEmbedInsertContent(embed_code, internal_id);
		
		return false;
	});

	// tabs
	$('.embed_section').live('click', function() {
		var section = $(this).attr('id');
		var url = elgg.config.wwwroot + 'embed/embed?active_section=' + section + '&internal_id=' + internal_id;
		$('#facebox .body .content').load(url);

		return false;
	});

	// pagination
	function elggPaginationClick() {
		$('#facebox .body .content').load($(this).attr('href'));
		return false;
	}

	$('#facebox .elgg-pagination a').live('click', elggPaginationClick);
});

</script>
