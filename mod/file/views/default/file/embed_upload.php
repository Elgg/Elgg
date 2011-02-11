<?php
/**
 * Files upload form for embed
 */

$access_id = get_default_access(elgg_get_logged_in_user_entity());
if ($categories = elgg_view('categories', $vars)) {
	$categories = "<p>$categories</p>";
}

// recycling the upload action so some of these options are a bit weird.
$form_body = '<p>' . elgg_view('input/file', array('internalname' => 'upload')) . '</p>';
$form_body .= '<p>' . elgg_echo('file:title') . ": " . elgg_view("input/text", array('internalname' => 'title')) . '</p>';
$form_body .= '<p>' . elgg_echo('file:desc') . ": " . elgg_view("input/text",array('internalname' => 'description')) . '</p>';
$form_body .= '<p>' . elgg_echo('file:tags') . ": " . elgg_view("input/tags", array('internalname' => 'tags')) . '</p>';
$form_body .= '<p>' . elgg_echo('access') . ": " . elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id)) . '</p>';
$form_body .= $categories;
$form_body .= elgg_view('input/hidden', array('internalname' => 'ajax', 'value' => TRUE));
$form_body .= '<p>' . elgg_view('input/submit', array('value' => elgg_echo('upload'))) . '</p>';

echo elgg_view('input/form', array(
	'body' => $form_body,
	'internalid' => 'file_embed_upload',
	'action' => 'action/file/upload',
));

?>

<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
$(document).ready(function() {
	// fire off the ajax upload
	$('#file_embed_upload').submit(function() {
		var options = {
			success: function(data) {
				var info = jQuery.parseJSON(data);

				if (info.status == 'success') {
					$('.popup .content').load('<?php echo elgg_get_site_url() . 'pg/embed/embed'; ?>?active_section=file');
				} else {
					$('.popup .content').find('form').prepend('<p>' + info.message + '</p>');
				}
			}
		};
		$(this).ajaxSubmit(options);
		return false;
	});
});
</script>
