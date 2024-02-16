import 'jquery';
import 'jquery-ui';
import '../jquery.ui.autocomplete.html.js';
import elgg from 'elgg';
import Ajax from 'elgg/Ajax';

/**
 * @param {HTMLElement} wrapper outer div
 * @constructor
 */
function EntityPicker(wrapper) {
	this.$wrapper = $(wrapper);
	this.$input = $('.elgg-input-entity-picker', wrapper);
	this.$ul = $('.elgg-entity-picker-list', wrapper);

	var EntityPicker = this,
		data = this.$wrapper.data();

	this.name = data.name || 'entities';
	this.matchOn = data.matchOn || 'entities';
	this.handler = data.handler || 'livesearch';
	this.limit = data.limit || 0;
	this.minLength = data.minLength || 2;
	this.isSealed = false;

	this.$input.autocomplete({
		source: function(request, response) {
			// note: "this" below will not be bound to the input, but rather
			// to an object created by the autocomplete component
			var Autocomplete = this;

			if (EntityPicker.isSealed) {
				return;
			}

			var ajax = new Ajax();
			ajax.path(EntityPicker.handler, {
				data: {
					term: Autocomplete.term,
					match_on: EntityPicker.getSearchType(),
					name: EntityPicker.name
				},
				method: 'GET',
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: EntityPicker.minLength,
		html: 'html',
		select: function(event, ui) {
			EntityPicker.addEntity(event, ui.item.guid, ui.item.html);
		},
		// turn off experimental live help - no i18n support and a little buggy
		messages: {
			noResults: '',
			results: function() {}
		}
	});

	this.$wrapper.on('click', '.elgg-autocomplete-item-remove', function(event) {
		EntityPicker.removeEntity(event);
	});

	this.enforceLimit();
}

EntityPicker.prototype = {
	/**
	 * Adds an entity to the selected entity list
	 *
	 * @param {Object} event
	 * @param {Number} guid  GUID of autocomplete item selected by user
	 * @param {String} html  HTML for autocomplete item selected by user
	 */
	addEntity: function(event, guid, html) {
		if (event.isDefaultPrevented()) {
			return;
		}
		
		// do not allow entities to be added multiple times
		if (!$('li[data-guid="' + guid + '"]', this.$ul).length) {
			this.$ul.append(html);
		}
		
		this.$input.val('');

		this.enforceLimit();

		event.preventDefault();
	},

	/**
	 * Removes an entity from the selected entities list
	 *
	 * @param {Object} event
	 */
	removeEntity: function(event) {
		$(event.target).closest('.elgg-entity-picker-list > li').remove();

		this.enforceLimit();

		event.preventDefault();
	},

	/**
	 * Make sure user can't add more than limit
	 */
	enforceLimit: function() {
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
	 * Don't allow user to add entities
	 */
	seal: function() {
		this.$input.prop('disabled', true);
		this.$wrapper.addClass('elgg-state-disabled');
		this.isSealed = true;
	},

	/**
	 * Allow user to add entities
	 */
	unseal: function() {
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
EntityPicker.setup = function(selector) {
	$(selector).each(function () {
		// we only want to wrap each picker once
		if (!$(this).data('initialized')) {
			new EntityPicker(this);
			$(this).data('initialized', 1);
		}
	});
};

export default EntityPicker;
