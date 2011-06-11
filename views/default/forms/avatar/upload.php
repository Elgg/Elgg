<?php
/**
 * Avatar upload form
 * 
 * @uses $vars['entity']
 */

?>
<div>
	<label><?php echo elgg_echo("avatar:upload"); ?></label><br />
	<?php echo elgg_view("input/file",array('name' => 'avatar')); ?>
</div>
<div class="elgg-form-footer">
	<?php echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid)); ?>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('upload'))); ?>
</div>
