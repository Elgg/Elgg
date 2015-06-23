<?php
/**
 * Image cropping view
 *
 * @uses vars['entity']
 */

?>
<div id="image-croppingtool" class="mtl ptm">
	<label><?php echo elgg_echo('image:crop:title'); ?></label>
	<br />
	<p>
		<?php echo elgg_echo("image:create:instructions"); ?>
	</p>
	<?php echo elgg_view_form('image/crop', array(), $vars); ?>
</div>
