<?php
$from_entity = elgg_extract('from_entity', $vars, '');
$to_entity = elgg_extract('to_entity', $vars, '');

$subject = elgg_extract('subject', $vars, '');
$message = elgg_extract('message', $vars, '');

?>
<div>
	<?php echo elgg_echo('projects_contact:from'); ?>
	<label><?php echo $from_entity->name; ?></label> / 
	<?php echo elgg_echo('projects_contact:to'); ?>
	<label><?php echo $to_entity->name; ?></label><br />
</div>
<div>
	<label><?php echo elgg_echo('projects_contact:subject'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'subject', 'value' => $subject)); ?>
</div>
<div>
	<label><?php echo elgg_echo('projects_contact:message'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'message', 'value' => $message)); ?>
</div>

<div class="elgg-foot">
<?php

	echo elgg_view('input/hidden', array('name' => 'fromGuid', 'value' => $from_entity->guid));
	echo elgg_view('input/hidden', array('name' => 'toGuid', 'value' => $to_entity->guid));

	echo elgg_view('input/submit', array('value' => elgg_echo("Enviar")));
?>
</div>
