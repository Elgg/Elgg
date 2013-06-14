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

if (0) { ?><script><?php }
?>

elgg.provide('elgg.walled_garden');

elgg.walled_garden.init = function () {

	$('.forgot_link').click(elgg.walled_garden.load('lost_password'));
	$('.registration_link').click(elgg.walled_garden.load('register'));

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

/**
 * Creates a closure for loading walled garden content through ajax
 *
 * @param {String} view Name of the walled garden view
 * @return {Object}
 */
elgg.walled_garden.load = function(view) {
	return function(event) {
		var id = '#elgg-walledgarden-' + view;
		id = id.replace('_', '-');
		// @todo display some visual element that indicates that loading of content is running
		elgg.get('walled_garden/' + view, {
			'success' : function(data) {
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
		event.preventDefault();
	};
};

elgg.register_hook_handler('init', 'system', elgg.walled_garden.init);
