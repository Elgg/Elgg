
elgg.provide('elgg.discussion');

/**
 * @param {Number} guid
 * @constructor
 */
elgg.discussion.Reply = function (guid) {
	this.guid = guid;
	this.$item = $('#elgg-object-' + guid);
};

elgg.discussion.Reply.prototype = {
	/**
	 * Get a jQuery-wrapped reference to the form
	 *
	 * @returns {jQuery} note: may be empty
	 */
	getForm: function () {
		return this.$item.find('.elgg-form-discussion-reply-save');
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

		// Get the form using ajax ajax/view/core/ajax/edit_comment
		elgg.ajax('ajax/view/ajax/discussion/reply/edit?guid=' + this.guid, {
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
			value = $form.find('textarea[name=description]').val();

		elgg.action('discussion/reply/save', {
			data: $form.serialize(),
			success: function(json) {
				if (json.status === 0) {
					// Update list item content
					that.$item.find('[data-role="discussion-reply-text"]').html(value);
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
 * Initialize discussion reply inline editing
 *
 * @return void
 */
elgg.discussion.init = function() {
	$(document).on('click', '.elgg-item-object-discussion_reply .elgg-menu-item-edit > a', function () {
		// store object as data in the edit link
		var dc = $(this).data('Reply'),
			guid;
		if (!dc) {
			guid = this.href.split('/').pop();
			dc = new elgg.discussion.Reply(guid);
			$(this).data('Reply', dc);
		}
		dc.toggleEdit();
		return false;
	});
};

elgg.register_hook_handler('init', 'system', elgg.discussion.init);