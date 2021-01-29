/** @module elgg/UserPicker */
define(['jquery', 'elgg', 'elgg/Ajax', 'jquery-ui/widgets/autocomplete', 'jquery.ui.autocomplete.html'], function($, elgg, Ajax) {

	/**
	 * @param {HTMLElement} wrapper outer div
	 * @constructor
	 * @alias module:elgg/UserPicker
	 */
	function UserPicker(wrapper) {
		this.$wrapper = $(wrapper);
		this.$input = $('.elgg-input-user-picker', wrapper);
		this.$ul = $('.elgg-user-picker-list', wrapper);

		var UserPicker = this,
			data = this.$wrapper.data();

		this.name = data.name || 'members';
		this.matchOn = data.matchOn || 'users';
		this.handler = data.handler || 'livesearch';
		this.limit = data.limit || 0;
		this.minLength = data.minLength || 2;
		this.isSealed = false;

		this.$input.autocomplete({
			source: function(request, response) {
				// note: "this" below will not be bound to the input, but rather
				// to an object created by the autocomplete component
				var Autocomplete = this;

				if (UserPicker.isSealed) {
					return;
				}

				var ajax = new Ajax();
				ajax.path(UserPicker.handler, {
					data: {
						term: Autocomplete.term,
						match_on: UserPicker.getSearchType(),
						name: UserPicker.name
					},
					method: 'GET',
					success: function(data) {
						response(data);
					}
				});
			},
			minLength: UserPicker.minLength,
			html: 'html',
			select: function(event, ui) {
				UserPicker.addUser(event, ui.item.guid, ui.item.html);
			},
			// turn off experimental live help - no i18n support and a little buggy
			messages: {
				noResults: '',
				results: function() {}
			}
		});

		this.$wrapper.on('click', '.elgg-autocomplete-item-remove', function(event) {
			UserPicker.removeUser(event);
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
		},

		/**
		 * Get search type
		 */
		getSearchType: function() {
			if (this.$wrapper.has('[type="checkbox"][name="match_on"]:checked').length) {
				return $('[type="checkbox"][name=match_on]:checked', this.$wrapper).val();
			}
			
			return this.matchOn;
		}
	};

	/**
	 * @param {String} selector
	 */
	UserPicker.setup = function(selector) {
		$(selector).each(function () {
			// we only want to wrap each picker once
			if (!$(this).data('initialized')) {
				new UserPicker(this);
				$(this).data('initialized', 1);
			}
		});
	};

	return UserPicker;
});
