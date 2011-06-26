<?php
/**
 * Embed tabs
 *
 * @uses $vars['sections']
 * @uses $vars['upload_sections']
 * @uses $vars['actibe_section']
 */

$sections = elgg_extract('sections', $vars, array());
$active_section = elgg_extract('active_section', $vars, array_shift(array_keys($sections)));
$upload_sections = elgg_extract('upload_sections', $vars, array());

$tabs = array();
foreach ($sections as $section_id => $section_info) {
	$tab = array(
		'title' => $section_info['name'],
		'url' => '#',
		'url_class' => 'embed-section',
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
		'url_class' => 'embed-section',
		'url_id' => 'upload',
		'selected' => ($active_section == 'upload')
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
