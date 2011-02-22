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

	echo "<div class='embed_modal_upload'>";
	echo "<p>" . elgg_echo('embed:upload_type') . "$input</p>";

	if (!$upload_content = elgg_view($upload_sections[$active_section]['view'])) {
		$upload_content = elgg_echo('embed:no_upload_content');
	}

	echo $upload_content . "</div>";

?>
	<?php //@todo: JS 1.8: ugly ?>
	<script type="text/javascript">
	$(function() {

		// change for dropdown
		$('#embed_upload').change(function() {
			var upload_section = $(this).val();
			var url = elgg.get_site_url() + 'pg/embed/embed?active_section=upload&active_upload_section=' + upload_section;
			$('#facebox .body .content').load(url);
		});

	});
	</script>
<?php

} else {
	echo elgg_echo('embed:no_upload_sections');
}
