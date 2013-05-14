<?php
/**
 * Form for sending an email announcement
 *
 * @package Elgg
 * @subpackage Core
 */

$subject_label = elgg_echo('admin:mass_mailout:subject:label');
$subject_control = elgg_view('input/text', array('name' => 'subject'));
$message_label = elgg_echo('admin:mass_mailout:message:label');
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
