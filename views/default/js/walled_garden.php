<?php
/**
 * Walled garden JavaScript
 *
 * @todo update for new JS lib
 */

$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));
$cancel_button = trim($cancel_button);

?>

$(document).ready(function() {

	$('.forgot_link').click(function(event) {
		$.get('walled_garden/lost_password', function(data) {
			$('.elgg-walledgarden-double').fadeToggle();
			$('.elgg-body-walledgarden').append(data);
			$(".elgg-form-user-requestnewpassword").find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
			$('.elgg-walledgarden-single').fadeToggle();
		});
		event.preventDefault();
	});

	$('.registration_link').click(function(event) {
		$.get('walled_garden/register', function(data) {
			$('.elgg-walledgarden-double').fadeToggle();
			$('.elgg-body-walledgarden').append(data);
			$('.elgg-form-register').find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
			$('.elgg-walledgarden-single').fadeToggle();
		});
		event.preventDefault();
	});

	$('input.elgg-button-cancel').live('click', function(event) {
		if ($('.elgg-walledgarden-single').is(':visible')) {
			$('.elgg-walledgarden-double').fadeToggle();
			$('.elgg-walledgarden-single').fadeToggle();
			$('.elgg-walledgarden-single').remove();
		}
		event.preventDefault();
	});
});