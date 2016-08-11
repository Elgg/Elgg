<?php

$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));

echo elgg_format_element('div', [
	'id' => 'elgg-walled-garden-cancel',
	'class' => 'hidden',
], $cancel_button);