<?php
/**
 * Discussion topic reply form bofy
 *
 * @uses $vars['entity']
 */


if (isset($vars['entity']) && elgg_is_logged_in()) {
?>
	<div>
		<label><?php echo elgg_echo("reply"); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'group_topic_post')); ?>
	</div>
<?php
	echo elgg_view('input/submit', array('value' => elgg_echo('reply')));

	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
}
