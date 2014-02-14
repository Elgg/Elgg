/**
 * Implements periodic auto saving of changes to the blog body via AJAX
 *
 * @package Blog
 */

elgg.provide('elgg.blog');

elgg.blog.saveDraftInterval = 60000;

elgg.blog.saveDraft = function() {

	var description = $('#blog-post-edit [name=description]').val();
	var title = $('#blog-post-edit [name=title]').val();

	if (!description ||  !title  || description === elgg.blog.oldDescription) {
		return;
	}

	elgg.action('action/blog/auto_save_revision', {
		data: $('#blog-post-edit').serialize(),
		beforeSend: function() {
			elgg.blog.oldDescription = $('#blog-post-edit [name="description"]').val();
		},
		success: function(response) {
			if (response.status >= 0) {
				$('#blog-post-edit [name=guid]').val(response.output.guid);
				$('#blog-post-edit .blog-save-status-time').html(response.output.msg);
			} else {
				$('#blog-post-edit .blog-save-status-time').html(elgg.echo('error'));
			}
		}
	});

}

elgg.blog.init = function() {

	if (!$('#blog-post-edit').length) {
		return;
	}

	// Store a copy of the blog body to compare before auto save
	elgg.blog.oldDescription = $('#blog-post-edit [name="description"]').val();

	setInterval(elgg.blog.saveDraft, elgg.blog.saveDraftInterval);
};

elgg.register_hook_handler('init', 'system', elgg.blog.init);