<?php
$fromGuid = elgg_extract('fromGuid', $vars, '');
$fromName = elgg_extract('fromName', $vars, '');

$toGuid = elgg_extract('toGuid', $vars, '');
$toName = elgg_extract('toName', $vars, '');

$subject = elgg_extract('subject', $vars, '');
$message = elgg_extract('message', $vars, '');

$project = elgg_extract('project', $vars);

?>
<div>
	<?php echo elgg_echo('projects_contact:from'); ?>
	<label><?php echo $fromName; ?></label> / 
	<?php echo elgg_echo('projects_contact:to'); ?>
	<label id="toName" value="<?php echo $toName; ?>"><?php echo $toName; ?></label><br />

	<label><?php echo elgg_echo('projects_contact:subject'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'subject', 'value' => $subject)); ?>

	<label><?php echo elgg_echo('projects_contact:message'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'message', 'value' => $message)); ?>
</div>

<div class="elgg-foot">
<?php

	echo elgg_view('input/hidden', array('name' => 'fromGuid', 'value' => $fromGuid));
	echo elgg_view('input/hidden', array('name' => 'toGuid', 'value' => $toGuid));

	echo elgg_view('input/submit', array('value' => elgg_echo("Enviar")));
?>
</div>
