<?php
/**
 * Special upload form
 */
$upload_sections = elgg_extract('upload_sections', $vars, array());
$active_section = get_input('active_upload_section', array_shift(array_keys($upload_sections)));

$options = array();

if ($upload_sections) {
	foreach ($upload_sections as $id => $info) {
		$options[$id] = $info['name'];
	}

	$input = elgg_view('input/dropdown', array(
		'name' => 'download_section',
		'options_values' => $options,
		'id' => 'embed_upload',
		'value' => $active_section
	));

	// hack this in for now as we clean up this mess
	$form_vars = array(
		'enctype' => 'multipart/form-data',
		'class' => 'elgg-form',
	);
	$upload_content = elgg_view_form('file/upload', $form_vars);
/*
	if (!$upload_content = elgg_view($upload_sections[$active_section]['view'])) {
		$upload_content = elgg_echo('embed:no_upload_content');
	}
*/
	echo "<div class='mbm'>" . elgg_echo('embed:upload_type') . "$input</div>";
	echo "<div class='embed-upload'>";
	echo $upload_content;
	echo "</div>";

} else {
	echo elgg_echo('embed:no_upload_sections');
}
