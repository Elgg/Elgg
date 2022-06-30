/**
 * Save draft through ajax
 */
define(['jquery', 'elgg/Ajax', 'elgg/i18n'], function($, Ajax, i18n) {
	
	// get a copy of the body to compare for auto save
	var oldDescription = $('form.elgg-form-blog-save').find('textarea[name=description]').val();

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
			$form.find('.blog-save-status-time').html(i18n.echo('error'));
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

	// preview button clicked
	$(document).on('click', '.elgg-form-blog-save button[name="preview"]', function(event) {
		event.preventDefault();
		
		var ajax = new Ajax();
		var formData = ajax.objectify('form.elgg-form-blog-save');
		
		if (!(formData.get('description') && formData.get('title'))) {
			return false;
		}
		
		// open preview in blank window
		ajax.action('blog/save', {
			data: formData,
			success: function(data) {
				$('form.elgg-form-blog-save').find('input[name=guid]').val(data.guid);
				window.open(data.url, '_blank').focus();
			}
		});
	});

	// start auto save interval
	setInterval(saveDraft, 60000);

	return {
		saveDraft: saveDraft
	};
});
