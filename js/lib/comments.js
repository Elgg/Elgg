
elgg.provide('elgg.comments');

/**
 * @param {Number} guid
 * @constructor
 */
elgg.comments.Comment = function (guid) {
	this.guid = guid;
	this.$item = $('#elgg-object-' + guid);
};

elgg.comments.Comment.prototype = {
	/**
	 * Get a jQuery-wrapped reference to the form
	 *
	 * @returns {jQuery} note: may be empty
	 */
	getForm: function () {
		return this.$item.find('.elgg-form-comment-save');
	},

	/**
	 * @param {Function} complete Optional function to run when animation completes
	 */
	hideForm: function (complete) {
		complete = complete || function () {};
		this.getForm().slideUp('fast', complete).data('hidden', 1);
	},

	showForm: function () {
		this.getForm().slideDown('medium').data('hidden', 0);
	},

	loadForm: function () {
		var that = this;

		// Get the form using ajax
		elgg.ajax('ajax/view/core/ajax/edit_comment?guid=' + this.guid, {
			success: function(html) {
				// Add the form to DOM
				that.$item.find('.elgg-body').first().append(html);

				that.showForm();

				var $form = that.getForm();

				$form.find('.elgg-button-cancel').on('click', function () {
					that.hideForm();
					return false;
				});

				// save
				$form.on('submit', function () {
					that.submitForm();
					return false;
				});
			}
		});
	},

	submitForm: function () {
		var that = this,
			$form = this.getForm(),
			value = $form.find('textarea[name=generic_comment]').val();

		elgg.action('comment/save', {
			data: $form.serialize(),
			success: function(json) {
				// https://github.com/kvz/phpjs/blob/master/LICENSE.txt
				function nl2br(content) {
					if (/<(?:p|br)\b/.test(content)) {
						// probably formatted already
						return content;
					}
					return content.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2');
				}

				if (json.status === 0) {
					// Update list item content
					if (json.output) {
						that.$item.find('[data-role="comment-text"]').replaceWith(json.output);
					} else {
						// action has been overridden and doesn't return comment content
						that.$item.find('[data-role="comment-text"]').html(nl2br(value));
					}
				}
				that.hideForm(function () {
					that.getForm().remove();
				});
			}
		});

		return false;
	},

	toggleEdit: function () {
		var $form = this.getForm();
		if ($form.length) {
			if ($form.data('hidden')) {
				this.showForm();
			} else {
				this.hideForm();
			}
		} else {
			this.loadForm();
		}
		return false;
	}
};

/**
 * Initialize comment inline editing
 * 
 * @return void
 */
elgg.comments.init = function() {
	$(document).on('click', '.elgg-item-object-comment .elgg-menu-item-edit > a', function () {
		// store object as data in the edit link
		var dc = $(this).data('Comment'),
			guid;
		if (!dc) {
			guid = this.href.split('/').pop();
			dc = new elgg.comments.Comment(guid);
			$(this).data('Comment', dc);
		}
		dc.toggleEdit();
		return false;
	});
};

elgg.register_hook_handler('init', 'system', elgg.comments.init);