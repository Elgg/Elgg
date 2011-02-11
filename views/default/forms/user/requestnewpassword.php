<?php
/**
 * Elgg forgotten password.
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<p class="mtm">
	<?php echo elgg_echo('user:password:text'); ?>
</p>
<p>
	<label><?php echo elgg_echo('username'); ?></label>
	<?php echo elgg_view('input/text', array('internalname' => 'username')); ?>
</p>
<?php echo elgg_view('input/captcha'); ?>
<p>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('request'))); ?>
</p>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('input[name=username]').focus();
	});
</script>