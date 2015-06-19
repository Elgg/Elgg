/** @module elgg/UserPicker */

define(['jquery', 'elgg', 'jquery.ui.autocomplete.html'], function ($, elgg) {
	/**
	 * @param {HTMLElement} wrapper outer div
	 * @constructor
	 * @alias module:elgg/UserPicker
	 *
	 * @todo move this to /js/classes ?
	 */
	function UserPicker(wrapper) {
		this.$wrapper = $(wrapper);
		this.$input = $('.elgg-input-user-picker', wrapper);
		this.$ul = $('.elgg-user-picker-list', wrapper);

		var self = this,
			data = this.$wrapper.data();

		this.name = data.name || 'members';
		this.handler = data.handler || 'livesearch';
		this.limit = data.limit || 0;
		this.minLength = data.minLength || 2;
		this.isSealed = false;

		this.$input.autocomplete({
			source: function(request, response) {
				// note: "this" below will not be bound to the input, but rather
				// to an object created by the autocomplete component

				if (self.isSealed) {
					return;
				}

				elgg.get(self.handler, {
					data: {
						term: this.term,
						"match_on[]": ($('[name=match_on]', self.$wrapper).attr('checked') ? 'friends' : 'users'),
						name: self.name
					},
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});
			},
			minLength: self.minLength,
			html: "html",
			select: function(event, ui) {
				self.addUser(event, ui.item.guid, ui.item.html);
			},
			// turn off experimental live help - no i18n support and a little buggy
			messages: {
				noResults: '',
				results: function() {}
			}
		});

		$('.elgg-user-picker-remove', this.$wrapper).live('click', function(event) {
			self.removeUser(event);
		});

		this.enforceLimit();
	}

	UserPicker.prototype = {
		/**
		 * Adds a user to the select user list
		 *
		 * @param {Object} event
		 * @param {Number} guid    GUID of autocomplete item selected by user
		 * @param {String} html    HTML for autocomplete item selected by user
		 */
		addUser : function(event, guid, html) {
			// do not allow users to be added multiple times
			if (!$('li[data-guid="' + guid + '"]', this.$ul).length) {
				this.$ul.append(html);
			}
			this.$input.val('');

			this.enforceLimit();

			event.preventDefault();
		},

		/**
		 * Removes a user from the select user list
		 *
		 * @param {Object} event
		 */
		removeUser : function(event) {
			$(event.target).closest('.elgg-user-picker-list > li').remove();

			this.enforceLimit();

			event.preventDefault();
		},

		/**
		 * Make sure user can't add more than limit
		 */
		enforceLimit : function() {
			if (this.limit) {
				if ($('li[data-guid]', this.$ul).length >= this.limit) {
					if (!this.isSealed) {
						this.seal();
					}
				} else {
					if (this.isSealed) {
						this.unseal();
					}
				}
			}
		},

		/**
		 * Don't allow user to add users
		 */
		seal : function() {
			this.$input.prop('disabled', true);
			this.$wrapper.addClass('elgg-state-disabled');
			this.isSealed = true;
		},

		/**
		 * Allow user to add users
		 */
		unseal : function() {
			this.$input.prop('disabled', false);
			this.$wrapper.removeClass('elgg-state-disabled');
			this.isSealed = false;
		}
	};

	/**
	 * @param {String} selector
	 */
	UserPicker.setup = function(selector) {
		elgg.register_hook_handler('init', 'system', function () {
			$(selector).each(function () {
				// we only want to wrap each picker once
				if (!$(this).data('initialized')) {
					new UserPicker(this);
					$(this).data('initialized', 1);
				}
			});
		});
	};

	return UserPicker;
});