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
<p class="mbn">
	<label><?php echo elgg_echo("generic_comments:text"); ?></label>
	<?php echo elgg_view('input/longtext', array('internalname' => 'generic_comment')); ?>
</p>
<?php

	echo elgg_view('input/hidden', array(
		'internalname' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
	echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));
}
