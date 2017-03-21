<?php
/**
 * Avatar upload form
 *
 * @uses $vars['entity']
 */

?>
<div>
	<label><?php echo elgg_echo("avatar:upload"); ?></label><br />
	<?php echo elgg_view("input/file", ['name' => 'avatar']); ?>
</div>
<div class="elgg-foot">
	<?php echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $vars['entity']->guid]); ?>
	<?php echo elgg_view('input/submit', ['value' => elgg_echo('upload')]); ?>
</div>
