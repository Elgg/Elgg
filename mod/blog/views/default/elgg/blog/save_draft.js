/**
 * Save draft through ajax
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function($, elgg, Ajax) {
	
	var oldDescription = '';

	var saveDraftCallback = function(data) {
		var $form = $('form.elgg-form-blog-save');
		
		if (data.success == true) {
			// update the guid input element for new posts that now have a guid
			$form.find('input[name=guid]').val(data.guid);
			
			oldDescription = $form.find('textarea[name=description]').val();
			
			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) {
				mins = '0' + mins;
			}
			$form.find('.blog-save-status-time').html(d.toLocaleDateString() + " @ " + d.getHours() + ":" + mins);
		} else {
			$form.find('.blog-save-status-time').html(elgg.echo('error'));
		}
	};

	var saveDraft = function() {
		var ajax = new Ajax(false);
		
		var formData = ajax.objectify('form.elgg-form-blog-save');
		
		formData.set('status', 'draft');
		
		// only save on changed content
		var description = formData.get('description');
		var title = formData.get('title');
		if (!(description && title) || (description == oldDescription)) {
			return false;
		}
		
		ajax.action('blog/auto_save_revision', {
			data: formData,
			success: saveDraftCallback
		});
	};

	var init = function() {
		// get a copy of the body to compare for auto save
		oldDescription = $('form.elgg-form-blog-save').find('textarea[name=description]').val();

		setInterval(saveDraft, 60000);
	};

	elgg.register_hook_handler('init', 'system', init);

	return {
		saveDraft: saveDraft
	};
});
