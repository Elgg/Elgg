<?php
$inline = elgg_extract('inline', $vars, false);

$reply = elgg_extract('entity', $vars);

if ($reply && $reply->canEdit()) {
	$submit_text = elgg_echo('save');
} else {
	$submit_text = elgg_echo('reply');
}

$cancel_button = '';
if ($reply) {
	$cancel_button = elgg_view('input/button', array(
		'value' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel mlm',
		'href' => $entity ? $entity->getURL() : '#',
	));
}

$submit_input = elgg_view('input/submit', array('value' => $submit_text));

if ($inline) {
	echo $submit_input;
} else {
	echo <<<FORM
	<div class="foot">
		$submit_input $cancel_button
	</div>
FORM;
}
