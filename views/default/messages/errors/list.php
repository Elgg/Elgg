<?php
/**
 * Elgg list errors
 * Lists error messages
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An array of error messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {

?>
<!-- used to fade out the system messages after 3 seconds -->
<script>
$(document).ready(function () {
	$('.messages_error').animate({opacity: 1.0}, 1000);
	$('.messages_error').animate({opacity: 1.0}, 5000);
	$('.messages_error').fadeOut('slow');

	$('span.closeMessages a').click(function () {
		$(".messages_error").stop();
		$('.messages_error').fadeOut('slow');
	return false;
	});

	$('div.messages_error').click(function () {
		$(".messages_error").stop();
		$('.messages_error').fadeOut('slow');
	return false;
	});

});
</script>

	<div class="messages_error">
	<span class="closeMessages"><a href="#"><?php echo elgg_echo('systemmessages:dismiss'); ?></a></span>

<?php
	foreach($vars['object'] as $error) {
		echo elgg_view('messages/errors/error',array('object' => $error));
	}
?>

	</div>
<?php
}