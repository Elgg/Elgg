<?php
/**
 * Elgg forgotten password.
 *
 * @package Elgg
 * @subpackage Core
 */

$username = elgg_extract('username', $vars, '');

$input_attrs = [
	'name' => 'username',
	'value' => $username,
];
if (!$username) {
	$input_attrs['autofocus'] = true;
}
?>

<div class="mtm">
	<?php echo elgg_echo('user:password:text'); ?>
</div>
<div>
	<label><?php echo elgg_echo('loginusername'); ?></label><br />
	<?php echo elgg_view('input/text', $input_attrs); ?>
</div>
<?php echo elgg_view('input/captcha'); ?>
<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('request'))); ?>
</div>
