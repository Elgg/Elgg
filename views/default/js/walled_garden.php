<?php
/**
 * Walled garden JavaScript
 *
 * @since 1.8
 */

// note that this assumes the button view is not using single quotes
$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));
$cancel_button = trim($cancel_button);

?>

elgg.provide('elgg.walled_garden');

elgg.walled_garden.init = function () {

	$('.forgot_link').click(elgg.walled_garden.load('lost_password'));
	$('.registration_link').click(elgg.walled_garden.load('register'));

	$('input.elgg-button-cancel').live('click', function(event) {
		if ($('.elgg-walledgarden-single').is(':visible')) {
			$('.elgg-walledgarden-double').fadeToggle();
			$('.elgg-walledgarden-single').fadeToggle();
			$('.elgg-walledgarden-single').remove();
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
		elgg.get('walled_garden/' + view, {
			'success' : function(data) {
				$('.elgg-body-walledgarden').append(data);
				$(id).find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
				$('#elgg-walledgarden-login').fadeToggle();
				$(id).fadeToggle();
			},
		});
		event.preventDefault();
    };
};

elgg.register_hook_handler('init', 'system', elgg.walled_garden.init);