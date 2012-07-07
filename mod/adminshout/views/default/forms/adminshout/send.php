<?php
/**
 * For for sending an email announcement
 */

$subject_label = elgg_echo('adminshout:subject:label');
$subject_control = elgg_view('input/text', array('name' => 'subject'));
$message_label = elgg_echo('adminshout:message:label');
$message_control = elgg_view('input/longtext', array('name' => 'message'));
$send_button = elgg_view('input/submit', array('value' => elgg_echo('send')));

echo <<< END
	<div>
		<label>$subject_label</label>$subject_control
	</div>
	<div>
		<label>$message_label</label>$message_control
	</div>
	<div class="elgg-foot">
		$send_button
	</div>
END;
