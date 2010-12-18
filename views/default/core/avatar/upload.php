<?php
/**
 * Avatar upload view
 *
 * @uses $vars['entity']
 */

$user_avatar = $vars['entity']->getIcon('medium');

?>

<p class="mtm">
	<?php echo elgg_echo('avatar:upload:instructions'); ?>
</p>

<div id="current-user-avatar">
	<label><?php echo elgg_echo('avatar:current'); ?></label>
	<?php echo "<img src=\"{$user_avatar}\" alt=\"avatar\" />"; ?>
</div>

<div id="avatar-upload">
<?php
	$form_params = array('enctype' => 'multipart/form-data');
	echo elgg_view_form('avatar/upload', $form_params, $vars);
?>
</div>
