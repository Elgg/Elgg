<?php
/**
 * Discussion topic add/edit form body
 * 
 */

$title = elgg_get_array_value('title', $vars, '');
$desc = elgg_get_array_value('description', $vars, '');
$status = elgg_get_array_value('status', $vars, '');
$tags = elgg_get_array_value('tags', $vars, '');
$access_id = elgg_get_array_value('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_get_array_value('container_guid', $vars);
$guid = elgg_get_array_value('guid', $vars, null);

?>
<p>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('internalname' => 'title', 'value' => $title)); ?>
</p>
<p>
	<label><?php echo elgg_echo('groups:topicmessage'); ?></label>
	<?php echo elgg_view('input/longtext', array('internalname' => 'description', 'value' => $desc)); ?>
</p>
<p>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('internalname' => 'tags', 'value' => $tags)); ?>
</p>
<p>
    <label><?php echo elgg_echo("groups:topicstatus"); ?></label><br />
	<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'status',
			'value' => $status,
			'options_values' => array(
				'open' => elgg_echo('groups:topicopen'),
				'closed' => elgg_echo('groups:topicclosed'),
			),
		));
	?>	
<p>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id)); ?>
</p>
<p>
<?php

echo elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('internalname' => 'topic_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
</p>
