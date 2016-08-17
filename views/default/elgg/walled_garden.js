/**
 * @module elgg/walled_garden
 */
define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax();

	var walled_garden = {
		/**
		 * Bind walled garden events
		 * @returns {void}
		 */
		init: function () {

			// Remove lightbox/popup bindings, if any
			$('.forgot_link,.registration_link').removeClass('elgg-lightbox');
			$('.forgot_link[rel="popup"],.registration_link[rel="popup"]').prop('rel', false);

			$(document).on('click', '.forgot_link', walled_garden.load('lost_password'));
			$(document).on('click', '.registration_link', walled_garden.load('register'));

			$(document).on('click', 'input.elgg-button-cancel', function (event) {
				var $wgs = $('.elgg-walledgarden-single');
				if ($wgs.is(':visible')) {
					$('.elgg-walledgarden-double').fadeToggle();
					$wgs.fadeToggle();
					$wgs.remove();
				}
				event.preventDefault();
			});

			// only run this function once
			walled_garden.init = elgg.nullFunction;
		},
		/**
		 * Creates a closure for loading walled garden content through ajax
		 *
		 * @param {String} view Name of the walled garden view
		 * @return {Object}
		 */
		load: function (view) {
			return function (event) {
				var id = '#elgg-walledgarden-' + view;
				id = id.replace('_', '-');

				ajax.path('walled_garden/' + view).done(function (data, statusText, jqXHR) {
					if (jqXHR.AjaxData.status === -1) {
						return;
					}

					var $wg = $('.elgg-body-walledgarden');
					$wg.append(data);

					$(id).find('input.elgg-button-submit').after($('#elgg-walled-garden-cancel').html());

					if (view === 'register' && $wg.hasClass('hidden')) {
						// this was a failed registration, display the register form ASAP
						$('#elgg-walledgarden-login').toggle(false);
						$(id).toggle();
						$wg.removeClass('hidden');
					} else {
						$('#elgg-walledgarden-login').fadeToggle();
						$(id).fadeToggle();
					}
				});

				event.preventDefault();
			};
		}
	}

	require(['elgg/ready'], function () {
		elgg.register_hook_handler('init', 'system', walled_garden.init);
	});

	// BC implementation
	// @todo: remove in 3.0
	elgg.provide('elgg.walled_garden');
	elgg.walled_garden.init = function () {
		elgg.deprecated_notice('elgg.walled_garden.init has been deprecated. Use elgg/walled_garden#init AMD method instead', '2.3');
		return walled_garden.init.apply(this, arguments);
	};
	elgg.walled_garden.load = function () {
		elgg.deprecated_notice('elgg.walled_garden.load has been deprecated. Use elgg/walled_garden#load AMD method instead', '2.3');
		return walled_garden.init.apply(this, arguments);
	};

	return walled_garden;
});