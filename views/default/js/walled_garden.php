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

	// add cancel button to inline forms
	$(".elgg-walledgarden-password").find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
	$('.elgg-walledgarden-register').find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');

	$(".forgot_link").click(function(event) {
		event.preventDefault();
		$(".elgg-walledgarden-password").fadeToggle();
	});

	$(".registration_link").click(function(event) {
		event.preventDefault();
		$(".elgg-walledgarden-register").fadeToggle();
	});

	$('input.elgg-button-cancel').click(function(event) {
		if ($(".elgg-walledgarden-password").is(':visible')) {
			$(".forgot_link").click();
		} else if ($('.elgg-walledgarden-register').is(':visible')) {
			$(".registration_link").click();
		}
		event.preventDefault();
	});
});