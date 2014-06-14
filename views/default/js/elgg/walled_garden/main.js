define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	
	var cancel_button = $('<input type="button">', {
		'class': 'elgg-button-cancel mlm',
		'value': elgg.echo('cancel')
	});

	/**
	 * Creates a closure for loading walled garden content through ajax
	 *
	 * @param {String} view Name of the walled garden view
	 * @return {Object}
	 */
	function createLoader(view) {
		return function(event) {
			var id = '#elgg-walledgarden-' + view;
			id = id.replace('_', '-');
			// @todo display some visual element that indicates that loading of content is running
			elgg.get('walled_garden/' + view, {
				'success' : function(data) {
					var $wg = $('.elgg-body-walledgarden');
					$wg.append(data);
					$(id).find('input.elgg-button-submit').after(cancel_button);

					if (view == 'register' && $wg.hasClass('hidden')) {
						// this was a failed registration, display the register form ASAP
						$('#elgg-walledgarden-login').toggle(false);
						$(id).toggle();
						$wg.removeClass('hidden');
					} else {
						$('#elgg-walledgarden-login').fadeToggle();
						$(id).fadeToggle();
					}
				}
			});
			event.preventDefault();
		};
	};

	function init() {
		$('.forgot_link').click(createLoader('lost_password'));
		$('.registration_link').click(createLoader('register'));

		$('input.elgg-button-cancel').live('click', function(event) {
			var $wgs = $('.elgg-walledgarden-single');
			if ($wgs.is(':visible')) {
				$('.elgg-walledgarden-double').fadeToggle();
				$wgs.fadeToggle();
				$wgs.remove();
			}
			event.preventDefault();
		});
	};


	elgg.register_hook_handler('init', 'system', init);
	
	
	return {
		init: init
	};
});
