
elgg.provide('elgg.comments');

/**
 * Get comment editing form via ajax
 * 
 * @return void
 */
elgg.comments.getForm = function() {
	var guid = $(this).attr('href').split('-').pop();
	var item = '#elgg-object-' + guid;
	var form = $(item).find('.elgg-form-comment-save');

	if (form.length == 0) {
		// Get the form using ajax
		elgg.ajax('comments', {
			data: {comment_guid: guid},
			success: function(json) {
				// Add the form to DOM
				$(item).find('.elgg-body').first().append(json);
				// Show the form
				$(item).find('.elgg-form-comment-save').slideDown('medium');
			}
		});
	}
};

/**
 * Save comment
 * 
 * @param {Object} event
 * @return void
 */
elgg.comments.save = function(event) {
	var data = $(this).serialize();
	var guid = $(this).find('input[name=comment_guid]').val();
	var value = $(this).find('textarea[name=generic_comment]').val();
	var item = '#elgg-object-' + guid;

	// Call the save action
	var form = elgg.action('comment/save', {
		data: data,
		success: function(json) {
			// Update list item content
			$(item).find('.elgg-output').html(value);
			// Hide the form
			$(item).find('.elgg-form-comment-save').slideUp('medium');
		}
	});

	event.preventDefault();
};

/**
 * Initialize comment inline editing
 * 
 * @return void
 */
elgg.comments.init = function() {
	$('.elgg-edit-comment').live('click', elgg.comments.getForm);
	$('.elgg-item-object-comment .elgg-form-comment-save').live('submit', elgg.comments.save);
};

elgg.register_hook_handler('init', 'system', elgg.comments.init);