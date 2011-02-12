<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {
?>
<div class="mbn">
	<label><?php echo elgg_echo("generic_comments:text"); ?></label>
	<?php echo elgg_view('input/longtext', array('internalname' => 'generic_comment')); ?>
</div>
<?php

	echo elgg_view('input/hidden', array(
		'internalname' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
	echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));
}
