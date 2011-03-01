<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */


if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'generic_comment'));
		echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
	} else {
?>
		<div>
			<label><?php echo elgg_echo("generic_comments:add"); ?></label>
			<?php echo elgg_view('input/longtext', array('name' => 'generic_comment')); ?>
		</div>
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
}