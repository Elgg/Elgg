/**
 * Save draft through ajax
 *
 * @package Blog
 */

elgg.provide('elgg.blog');

elgg.blog.saveDraft = function() {

	var description = $('#blog-post-edit').find('[name=description]').val();
	var title = $('#blog-post-edit').find('[name=title]').val();

	if (!(description && title) || (description == elgg.blog.oldDescription)) {
		return;
	}

	elgg.action('action/blog/auto_save_revision', {
		data: $('#blog-post-edit').serialize(),
		beforeSend: function() {
			elgg.blog.oldDescription = $('#blog-post-edit').find('[name="description"]').val();
		},
		success: function(response) {
			if (response.status >= 0) {
				$('#blog-post-edit').find('[name=guid]').val(response.output.guid);
				$('#blog-post-edit').find('.blog-save-status-time').html(response.output.msg);
			} else {
				$('#blog-post-edit').find('.blog-save-status-time').html(elgg.echo('error'));
			}
		}
	});

}

elgg.blog.init = function() {

	if (!$('#blog-post-edit').length) {
		return;
	}

	// Store a copy of the blog body to compare before auto save
	elgg.blog.oldDescription = $('#blog-post-edit').find('[name="description"]').val();

	setInterval(elgg.blog.saveDraft, 60000);
};

elgg.register_hook_handler('init', 'system', elgg.blog.init);