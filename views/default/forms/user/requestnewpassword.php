<?php
/**
 * Elgg forgotten password.
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div class="mtm">
	<?php echo elgg_echo('user:password:text'); ?>
</div>
<div>
	<label><?php echo elgg_echo('username'); ?></label>
	<?php echo elgg_view('input/text', array('name' => 'username')); ?>
</div>
<?php echo elgg_view('input/captcha'); ?>
<div>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('request'))); ?>
</div>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('input[name=username]').focus();
	});
</script>