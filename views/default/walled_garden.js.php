<?php
/**
 * Walled garden JavaScript
 *
 * @since 1.8
 */

$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));
$cancel_button = json_encode($cancel_button);

?>
//<script>

require(['elgg', 'jquery'], function (elgg, $) {
	elgg.provide('elgg.walled_garden');

	elgg.walled_garden.init = function () {
		// make sure it is loaded before using it in the click events
		require(['elgg/spinner']);
		$('.forgot_link').click(elgg.walled_garden.load('lost_password'));
		$('.registration_link').click(elgg.walled_garden.load('register'));

		$(document).on('click', 'input.elgg-button-cancel', function(event) {
			var $wgs = $('.elgg-walledgarden-single');
			if ($wgs.is(':visible')) {
				$('.elgg-walledgarden-double').fadeToggle();
				$wgs.fadeToggle();
				$wgs.remove();
			}
			event.preventDefault();
		});
	};

	/**
	 * Creates a closure for loading walled garden content through ajax
	 *
	 * @param {String} view Name of the walled garden view
	 * @return {Object}
	 */
	elgg.walled_garden.load = function(view) {
		return function(event) {
			require(['elgg/spinner'], function(spinner) {
				var id = '#elgg-walledgarden-' + view;
				id = id.replace('_', '-');
				// @todo display some visual element that indicates that loading of content is running
				elgg.get('walled_garden/' + view, {
					beforeSend: spinner.start,
					complete: spinner.stop,
					success: function(data) {
						var $wg = $('.elgg-body-walledgarden');
						$wg.append(data);
						$(id).find('input.elgg-button-submit').after(<?php echo $cancel_button; ?>);

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

			});

			event.preventDefault();
		};
	};

	elgg.register_hook_handler('init', 'system', elgg.walled_garden.init);
});
