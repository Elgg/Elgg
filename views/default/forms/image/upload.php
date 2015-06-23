<?php
/**
 * Image upload form
 *
 * @uses $vars['entity']
 */

?>
<div>
	<label><?php echo elgg_echo("image:upload"); ?></label><br />
	<?php
		echo elgg_view("input/file", array(
			'name' => 'image',
		));
	?>
</div>
<div class="elgg-foot">
	<?php
		echo elgg_view('input/hidden', array(
			'name' => 'guid',
			'value' => $vars['entity']->guid,
		));

		echo elgg_view('input/submit', array(
			'value' => elgg_echo('upload'),
		));
	?>
</div>
