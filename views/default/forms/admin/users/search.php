<?php

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'text',
			'#class' => 'elgg-field-stretch',
			'name' => 'q',
			'placeholder' => elgg_echo('search'),
			'value' => get_input('q'),
		],
		[
			'#type' => 'submit',
			'value' => elgg_echo('search'),
		],
	],
	'align' => 'horizontal',
]);
