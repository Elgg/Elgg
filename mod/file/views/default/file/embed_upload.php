<?php
/**
 * Files upload form for embed
 */

echo elgg_view_form('file/upload', array('id' => 'file_embed_upload'), array('ajax' => TRUE));

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
					$('.popup .content').load('<?php echo elgg_get_site_url() . 'embed/embed'; ?>?active_section=file');
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
