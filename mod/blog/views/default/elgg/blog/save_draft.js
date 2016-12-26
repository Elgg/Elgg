/**
 * Save draft through ajax
 *
 * @package Blog
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax(false);

	var saveDraftCallback = function (data, textStatus, jqXHR) {
		if (jqXHR.AjaxData.status === -1) {
			$(".blog-save-status-time").html(elgg.echo('error'));
			return;
		}

		var form = $('form[id=blog-post-edit]');

		// update the guid input element for new posts that now have a guid
		form.find('input[name=guid]').val(data.guid);

		oldDescription = form.find('textarea[name=description]').val();

		$(".blog-save-status-time").html(data.status_time);
	};

	var saveDraft = function () {
		if (typeof tinyMCE !== 'undefined') {
			tinyMCE.triggerSave();
		}

		// only save on changed content
		var $form = $('form[id=blog-post-edit]');
		var description = $form.find('textarea[name=description]').val();
		var title = $form.find('input[name=title]').val();

		if (!description || !title || description === oldDescription) {
			return false;
		}

		return ajax.action('blog/auto_save_revision', {
			data: ajax.objectify($form)
		}).done(saveDraftCallback);
	};

	var init = function() {
		// get a copy of the body to compare for auto save
		oldDescription = $('form[id=blog-post-edit]').find('textarea[name=description]').val();

		setInterval(saveDraft, 60000);
	};

	elgg.register_hook_handler('init', 'system', init);

	return {
		saveDraft: saveDraft
	};
});
