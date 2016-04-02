/**
 * Discussion Reply constructor
 * 
 * @module elgg/DiscussionReply
 */
define('elgg/DiscussionReply', ['elgg', 'jquery'], function (elgg, $) {

	/**
	 * @param {Number} guid
	 * @constructor
	 */
	return function (guid) {
		this.guid = guid;
		this.$item = $('#elgg-object-' + guid);

		/**
		 * Get a jQuery-wrapped reference to the form
		 *
		 * @returns {jQuery} note: may be empty
		 */
		this.getForm = function () {
			return this.$item.find('.elgg-form-discussion-reply-save');
		};
		/**
		 * @param {Function} complete Optional function to run when animation completes
		 */
		this.hideForm = function (complete) {
			complete = complete || function () {};
			this.getForm().slideUp('fast', complete).data('hidden', 1);
		};
		this.showForm = function () {
			this.getForm().slideDown('medium').data('hidden', 0);
		};
		this.loadForm = function () {
			var that = this;

			// Get the form using ajax ajax/view/core/ajax/edit_comment
			elgg.ajax('ajax/view/ajax/discussion/reply/edit?guid=' + this.guid, {
				success: function (html) {
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
		};
		this.submitForm = function () {
			var that = this,
					$form = this.getForm(),
					value = $form.find('textarea[name=description]').val();

			elgg.action('discussion/reply/save', {
				data: $form.serialize(),
				success: function (json) {
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
		};
		this.toggleEdit = function () {
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
		};
	};
});

/**
 * Elgg discussions module
 * 
 * @module elgg/discussion
 */
define('elgg/discussion', ['jquery', 'elgg/DiscussionReply', 'elgg/init'], function ($, DiscussionReply) {
	var discussion = {
		ready: false,
		init: function () {
			if (discussion.ready) {
				return;
			}
			$(document).on('click', '.elgg-item-object-discussion_reply .elgg-menu-item-edit > a', function () {
				// store object as data in the edit link
				var dc = $(this).data('Reply'),
						guid;
				if (!dc) {
					guid = this.href.split('/').pop();
					dc = new DiscussionReply(guid);
					$(this).data('Reply', dc);
				}
				dc.toggleEdit();
				return false;
			});
			discussion.ready = true;
		}
	};
	return discussion;
});

/**
 * Initialize discussions
 */
require(['elgg', 'elgg/discussion', 'elgg/DiscussionReply'], function (elgg, discussion, DiscussionReply) {

	elgg.provide('elgg.discussion');

	/**
	 * Initialize discussion reply inline editing
	 *
	 * @return void
	 * @deprecated 2.2
	 */
	elgg.discussion.init = function () {
		elgg.deprecated_notice('elgg.discussion.init() has been deprecated. Use elgg/discussion AMD module instead.', '2.2');
		discussion.init();
	};

	/**
	 * @param {Number} guid
	 * @constructor
	 * @deprecated 2.2
	 */
	elgg.discussion.Reply = function (guid) {
		elgg.deprecated_notice('elgg.discussion.Reply constructor has been deprecated. Use elgg/DiscussionReply AMD module instead.', '2.2');
		return new DiscussionReply(guid);
	};

	discussion.init();
});
