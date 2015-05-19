define(function (require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	var spinner = require('elgg/spinner');

	/**
	 * @param {Number} guid
	 * @constructor
	 */
	function Comment(guid) {
		this.guid = guid;
		this.$item = $('#elgg-object-' + guid);
	}

	Comment.prototype = {
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
			elgg.ajax('ajax/view/core/ajax/edit_comment?guid=' + that.guid, {
				beforeSend: spinner.start,
				complete: spinner.stop,
				success: function(html) {
					// Add the form to DOM
					var $body = that.$item.find('.elgg-body:first').append(html);

					// attach behaviors
					require(['elgg/behaviors'], function (behaviors) {
						behaviors.attach($body);
					});

					that.showForm();

					var $form = that.getForm();

					$form.find('.elgg-button-cancel').on('click', function () {
						that.hideForm();
						return false;
					});

					// save
					$form.on('submit', function () {
						that.submitForm(spinner);
						return false;
					});
				}
			});
		},

		submitForm: function (spinner) {
			var that = this,
				$form = this.getForm(),
				value = $form.find('textarea[name=generic_comment]').val();

			elgg.action('comment/save', {
				data: $form.serialize(),
				beforeSend: spinner.start,
				complete: spinner.stop,
				success: function(json) {
					if (json.status === 0) {
						// Update list item content
						// @todo 1.x returns HTML, call behaviors.attach on it
						that.$item.find('[data-role="comment-text"]').html(value);
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

	$(document).on('click', '.elgg-item-object-comment .elgg-menu-item-edit > a', function () {
		// store object as data in the edit link
		var dc = $(this).data('Comment'),
			guid;
		if (!dc) {
			guid = this.href.split('/').pop();
			dc = new Comment(guid);
			$(this).data('Comment', dc);
		}
		dc.toggleEdit();
		return false;
	});

	return Comment;
});
