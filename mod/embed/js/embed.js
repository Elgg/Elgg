$(function() {

	// change for dropdown
	$('#embed_upload').live('change', function() {
		var upload_section = $(this).val();
		var url = elgg.get_site_url() + 'embed/embed?active_section=upload&active_upload_section=' + upload_section;
		$('#facebox .body .content').load(url);
	});

});