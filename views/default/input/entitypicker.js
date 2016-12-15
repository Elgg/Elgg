define(['jquery', 'elgg', 'input/autocomplete'], function($, elgg, autocomplete) {

	/**
	 * @param {HTMLElement} wrapper outer div
	 * @constructor
	 */
	function EntityPicker(wrapper) {
		this.$wrapper = $(wrapper);
		this.$input = $('.elgg-input-autocomplete', wrapper);
		this.$ul = $('.elgg-entity-picker-list', wrapper);

		var self = this,
			data = this.$wrapper.data();

		this.limit = data.limit || 0;

		this.isSealed = false;
		
		
		autocomplete.init(this.$input, {
			select: function(event, ui) {
				self.addEntity(event, ui.item.guid, ui.item.html);
			},
		});
/*
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
						"match_on[]": ($('[name=match_on]', self.$wrapper).prop('checked') ? 'friends' : 'users'),
						name: self.name
					},
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});
			},


			
		});
*/
		this.$wrapper.on('click', '.elgg-entity-picker-remove', function(event) {
			self.removeEntity(event);
		});

		this.enforceLimit();
	}

	EntityPicker.prototype = {
		/**
		 * Adds a user to the select user list
		 *
		 * @param {Object} event
		 * @param {Number} guid    GUID of autocomplete item selected by user
		 * @param {String} html    HTML for autocomplete item selected by user
		 */
		addEntity : function(event, guid, html) {
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
		removeEntity : function(event) {
			$(event.target).closest('.elgg-entity-picker-list > li').remove();

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
	EntityPicker.init = function(selector) {
		
		$(selector).each(function () {
			new EntityPicker(this);
		});
	};

	return EntityPicker;
});