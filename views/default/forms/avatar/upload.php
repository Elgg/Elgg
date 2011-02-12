<?php
/**
 * Avatar upload form
 * 
 * @uses $vars['entity']
 */

echo elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $vars['entity']->guid));
?>
<div>
	<label><?php echo elgg_echo("avatar:upload"); ?></label><br />
	<?php echo elgg_view("input/file",array('internalname' => 'avatar')); ?>
<br />
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('upload'))); ?>
</div>
