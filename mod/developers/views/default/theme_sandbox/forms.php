<?php
$ipsum = elgg_view('developers/ipsum');
?>
<form action="#">
	<fieldset>
		<legend>Fieldset Legend</legend>
		<?php
		echo elgg_view_field([
			'#type' => 'text',
			'required' => true,
			'name' => 'f1',
			'id' => 'f1',
			'value' => 'input text',
			'#label' => 'Text input (.elgg-input-text):',
			'#help' => 'This is how help text looks',
		]);

		echo elgg_view_field([
			'#type' => 'password',
			'name' => 'f2',
			'id' => 'f2',
			'value' => 'password',
			'#label' => 'Password input (.elgg-input-password):',
		]);

		echo elgg_view_field([
			'#type' => 'radio',
			'name' => 'f3',
			'id' => 'f3',
			'options' => [
				'a (.elgg-input-radio)' => 1,
				'b (.elgg-input-radio)' => 2
			],
			'value' => 2,
			'#label' => 'Radio input (.elgg-input-radios):',
		]);

		echo elgg_view_field([
			'#type' => 'radio',
			'name' => 'f3a',
			'id' => 'f3a',
			'options_values' => [
				'a' => 'a (.elgg-input-radio)',
				'b' => 'b (.elgg-input-radio)',
				[
					'text' => 'c (.elgg-input-radio) from array',
					'value' => 'c',
					'title' => 'c (.elgg-input-radio) from array',
				],
			],
			'value' => 'c',
			'#label' => 'Radio input (.elgg-input-radios) with options_values:',
		]);

		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4s',
			'id' => 'f4s',
			'value' => 1,
			'default' => false,
			'required' => true,
			'label' => 'a (.elgg-input-checkbox)',
			'#help' => 'Single checkbox .elgg-input-checkbox wrapped in .elgg-input-single-checkbox (only label)',
		]);

		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4sa',
			'id' => 'f4sa',
			'value' => 1,
			'default' => false,
			'required' => true,
			'#label' => 'a (.elgg-input-checkbox)',
			'#help' => 'Single checkbox .elgg-input-checkbox wrapped in .elgg-input-single-checkbox (only #label)',
		]);

		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4sb',
			'id' => 'f4sb',
			'value' => 1,
			'default' => false,
			'required' => true,
			'#help' => 'Single checkbox .elgg-input-checkbox wrapped in .elgg-input-single-checkbox (no label)',
		]);

		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4s1',
			'id' => 'f4s1',
			'value' => 1,
			'switch' => true,
			'default' => false,
			'required' => true,
			'#label' => 'a (.elgg-input-checkbox) with switch style',
			'#help' => 'Single checkbox .elgg-input-checkbox ',
		]);
		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4s1a',
			'id' => 'f4s1a',
			'value' => 1,
			'switch' => true,
			'default' => false,
			'required' => true,
			'#help' => 'Switch styled checkbox without a label',
		]);
		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4s1b',
			'id' => 'f4s1b',
			'value' => 1,
			'switch' => true,
			'disabled' => true,
			'default' => false,
			'required' => true,
			'#label' => 'a (.elgg-input-checkbox) with disabled switch style',
			'#help' => 'Single checkbox .elgg-input-checkbox ',
		]);

		echo elgg_view_field([
			'#type' => 'checkbox',
			'name' => 'f4s2',
			'id' => 'f4s2',
			'value' => 1,
			'default' => false,
			'required' => true,
			'#label' => 'a (.elgg-input-checkbox) - Field label',
			'label' => 'a (.elgg-input-checkbox) - Input label',
			'#help' => 'Single checkbox .elgg-input-checkbox wrapped in .elgg-input-single-checkbox (label and #label)',
		]);

		echo elgg_view_field([
			'#type' => 'checkboxes',
			'name' => 'f4',
			'id' => 'f4',
			'options' => [
				'a (.elgg-input-checkbox)' => 1,
				'b (.elgg-input-checkbox)' => 2
			],
			'value' => 2,
			'#label' => 'Checkboxes input (.elgg-input-checkboxes):',
		]);

		echo elgg_view_field([
			'#type' => 'checkboxes',
			'name' => 'f4a',
			'id' => 'f4a',
			'switch' => true,
			'options' => [
				'a (.elgg-input-checkbox)' => 1,
				'b (.elgg-input-checkbox)' => 2
			],
			'#label' => 'Checkboxes input (.elgg-input-checkboxes) with switch style:',
		]);

		echo elgg_view_field([
			'#type' => 'checkboxes',
			'name' => 'f4b',
			'id' => 'f4b',
			'options_values' => [
				'a' => 'a (.elgg-input-checkbox)',
				'b' => 'b (.elgg-input-checkbox)',
				[
					'text' => 'c (.elgg-input-checkbox) from array',
					'value' => 'c',
					'title' => 'c (.elgg-input-checkbox) from array',
				],
			],
			'value' => ['a', 'c'],
			'#label' => 'Checkboxes input (.elgg-input-checkboxes) with options_values:',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f5',
			'id' => 'f5',
			'options' => [
				'option 1',
				'option 2',
				[
					'text' => 'disabled',
					'disabled' => true,
				],
			],
			'value' => 'option 2',
			'#label' => 'Select input (dropdown) (.elgg-input-select) with a disabled option:',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f50',
			'id' => 'f50',
			'options' => [
				'option 1',
				[
					'label' => 'optgroup label',
					'options' => [
						'value 1a' => 'option 1a',
						'value 1b' => 'option 1b',
						[
							'text' => 'option 1c',
							'value' => 'value 1c',
						]
					]
				],
				'option 2',
				[
					'text' => 'disabled',
					'disabled' => true,
				],
			],
			'#label' => 'Select input (dropdown) (.elgg-input-select) with an optgroup:',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f51[]',
			'id' => 'f51',
			'options_values' => [
				'value 1' => 'option 1',
				'value 2' => 'option 2',
				'value 3' => [
					'text' => 'option 3',
				],
				'value 4' => 'option 4',
			],
			'multiple' => true,
			'#label' => 'Select input (multiselect) (.elgg-input-select):',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f52[]',
			'id' => 'f52',
			'options_values' => [
				'value 1' => 'option 1',
				'value 2' => 'option 2',
				'value 3' => [
					'text' => 'option 3',
				],
				'value 4' => 'option 4',
			],
			'multiple' => true,
			'value' => ['value 1', 'value 3'],
			'#label' => 'Select input (multiselect) (.elgg-input-select) with options_values and multiple values selected:',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f521[]',
			'id' => 'f521',
			'options' => [
				'option 1',
				'option 2',
				'option 3',
				'option 4',
			],
			'multiple' => true,
			'value' => ['option 1', 'option 3'],
			'#label' => 'Select input (multiselect) (.elgg-input-select) with options and multiple values selected:',
		]);

		echo elgg_view_field([
			'#type' => 'select',
			'name' => 'f53[]',
			'id' => 'f53',
			'options_values' => [
				'value 1' => 'option 1',
				'value 2' => 'option 2',
				'value 3' => [
					'text' => 'option 3',
				],
				[
					'label' => 'optgroup label',
					'options' => [
						'value 1a' => 'option 1a',
						'value 1b' => 'option 1b',
						[
							'text' => 'option 1c',
							'value' => 'value 1c',
						]
					]
				],
				'value 4' => 'option 4',
			],
			'multiple' => true,
			'#label' => 'Select input (multiselect) with optgroup (.elgg-input-select):',
		]);

		echo elgg_view_field([
			'#type' => 'access',
			'name' => 'f6',
			'id' => 'f6',
			'value' => ACCESS_PUBLIC,
			'#label' => 'Access input (.elgg-input-access):',
		]);

		echo elgg_view_field([
			'#type' => 'file',
			'name' => 'f7',
			'id' => 'f7',
			'#label' => 'File input (.elgg-input-file):',
		]);

		echo elgg_view_field([
			'#type' => 'file',
			'name' => 'f7-with-value',
			'id' => 'f7-withe-value',
			'value' => true,
			'#label' => 'File input with value (.elgg-input-file):',
		]);

		echo elgg_view_field([
			'#type' => 'url',
			'name' => 'f8',
			'id' => 'f8',
			'value' => 'http://elgg.org/',
			'#label' => 'URL input (.elgg-input-url):',
		]);

		echo elgg_view_field([
			'#type' => 'tags',
			'name' => 'f9',
			'id' => 'f9',
			'value' => 'one, two, three',
			'#label' => 'Tags input (.elgg-input-tags):',
		]);

		echo elgg_view_field([
			'#type' => 'tags',
			'name' => 'f9b',
			'id' => 'f9b',
			'data-tagify-opts' => json_encode(['whitelist' => ['one', 'two', 'three'], 'dropdown' => ['enabled' => 0]]),
			'#label' => 'Tags input (.elgg-input-tags) with custom options:',
		]);

		echo elgg_view_field([
			'#type' => 'email',
			'name' => 'f101-email',
			'id' => 'f101-email',
			'value' => 'noone@elgg.org',
			'#label' => 'Email input (.elgg-input-email):',
		]);

		echo elgg_view_field([
			'#type' => 'tel',
			'name' => 'f102-tel',
			'id' => 'f102-tel',
			'value' => '123-12-123',
			'#label' => 'Telephone input (.elgg-input-tel):',
		]);

		echo elgg_view_field([
			'#type' => 'autocomplete',
			'name' => 'f11a',
			'id' => 'f11a',
			'match_on' => 'groups',
			'#label' => 'Groups autocomplete input (.elgg-input-autocomplete):',
		]);

		$groups = elgg_get_entities([
			'types' => 'group',
			'limit' => 1,
		]);

		echo elgg_view_field([
			'#type' => 'autocomplete',
			'name' => 'f11c',
			'id' => 'f11c',
			'match_on' => 'groups',
			'value' => ($groups) ? $groups[0]->guid : null,
			'#label' => 'Groups autocomplete input (.elgg-input-autocomplete) with initial value:',
		]);

		echo elgg_view_field([
			'#type' => 'autocomplete',
			'name' => 'f11b',
			'id' => 'f11b',
			'match_on' => 'users',
			'#label' => 'Users autocomplete input (.elgg-input-autocomplete):',
		]);

		echo elgg_view_field([
			'#type' => 'autocomplete',
			'name' => 'f11d',
			'id' => 'f11d',
			'match_on' => 'users',
			'value' => elgg_get_logged_in_user_entity()->username,
			'#label' => 'Users autocomplete input (.elgg-input-autocomplete) with initial value:',
		]);

		echo elgg_view_field([
			'#type' => 'date',
			'name' => 'f12',
			'id' => 'f12',
			'value' => '2012-12-31',
			'#label' => 'Date input (.elgg-input-date):',
		]);

		$year = date('Y');
		echo elgg_view_field([
			'#type' => 'date',
			'name' => 'f12-custom',
			'id' => 'f12-custom',
			'value' => "$year/02/01",
			'timestamp' => true,
			'datepicker_options' => [
				'dateFormat' => 'yy/mm/dd',
				'changeMonth' => false,
				'changeYear' => false,
				'minDate' => "$year/01/15",
				'maxDate' => "$year/02/15",
			],
			'#label' => 'Date input (.elgg-input-date) with custom options:',
			'#help' => 'Select a date from 15 Jan to 15 Feb',
		]);

		echo elgg_view_field([
			'#type' => 'time',
			'name' => 'f121-time',
			'id' => 'f121-time',
			'#label' => 'Time input (.elgg-input-time):',
		]);

		echo elgg_view_field([
			'#type' => 'time',
			'name' => 'f122-time',
			'id' => 'f122-time',
			'value' => new DateTime(),
			'min' => 8 * 60 * 60,
			'max' => 20 * 60 * 60,
			'step' => 30 * 60,
			'#label' => 'Time input (.elgg-input-time) with custom options:',
			'#help' => 'Select time between 8:00 and 20:00',
			'timestamp' => true,
		]);

		echo elgg_view_field([
			'#type' => 'datetime-local',
			'name' => 'f12-datetime-local',
			'id' => 'f12-datetime-local',
			'value' => '2012-12-31T11:59',
			'#label' => 'DateTime input (.elgg-input-datetime-local):',
			'#help' => 'Select date and time',
		]);

		echo elgg_view_field([
			'#type' => 'month',
			'name' => 'f12-month',
			'id' => 'f12-month',
			'value' => '2012-12',
			'#label' => 'Month input (.elgg-input-month):',
			'#help' => 'Select month',
		]);

		echo elgg_view_field([
			'#type' => 'week',
			'name' => 'f12-week',
			'id' => 'f12-week',
			'value' => '2012-W53',
			'#label' => 'Week input (.elgg-input-week):',
			'#help' => 'Select week',
		]);

		echo elgg_view_field([
			'#type' => 'userpicker',
			'name' => 'f13',
			'id' => 'f13',
			'#label' => 'User picker input (.elgg-user-picker):',
		]);

		echo elgg_view_field([
			'#type' => 'userpicker',
			'name' => 'f13',
			'id' => 'f13',
			'value' => array_map(function ($e) {
				return $e->guid;
			}, elgg_get_entities(['types' => 'user', 'limit' => 5])),
			'#label' => 'User picker input (.elgg-user-picker) with values:',
		]);

		echo elgg_view_field([
			'#type' => 'userpicker',
			'name' => 'f16',
			'id' => 'f16',
			'limit' => 1,
			'#label' => 'User picker input (with max 1 results) (.elgg-user-picker):',
		]);

		echo elgg_view_field([
			'#type' => 'friendspicker',
			'name' => 'f13a',
			'id' => 'f13a',
			'#label' => 'Friend picker input (.elgg-user-picker):',
		]);
		
		echo elgg_view_field([
			'#type' => 'grouppicker',
			'name' => 'f13b',
			'id' => 'f13b',
			'#label' => 'Groups picker input (.elgg-input-grouppicker):',
		]);
		
		echo elgg_view_field([
			'#type' => 'objectpicker',
			'name' => 'f13c',
			'id' => 'f13c',
			'#label' => 'Object picker input (.elgg-input-objectpicker):',
		]);

		echo elgg_view_field([
			'#type' => 'plaintext',
			'name' => 'f15',
			'id' => 'f15',
			'value' => $ipsum,
			'#label' => 'Plain textarea input (.elgg-input-plaintext):',
		]);

		echo elgg_view_field([
			'#type' => 'longtext',
			'name' => 'f14',
			'id' => 'f14',
			'value' => $ipsum,
			'#label' => 'Long textarea input (.elgg-input-longtext):',
		]);

		echo elgg_view_field([
			'#type' => 'longtext',
			'name' => 'f14a',
			'id' => 'f14a',
			'value' => $ipsum,
			'editor' => false,
			'#label' => 'Long textarea input (.elgg-input-longtext) with a disabled editor:',
		]);

		echo elgg_view_field([
			'#type' => 'longtext',
			'name' => 'f14b',
			'id' => 'f14b',
			'value' => $ipsum,
			'visual' => false,
			'#label' => 'Long textarea input (.elgg-input-longtext) without a visual editor activated by default:',
		]);

		echo elgg_view_field([
			'#type' => 'longtext',
			'name' => 'f14c',
			'id' => 'f14c',
			'value' => $ipsum,
			'editor_type' => 'simple',
			'#label' => 'Long textarea input (.elgg-input-longtext) with the editor_type configured as "simple":',
		]);

		echo elgg_view_field([
			'#type' => 'number',
			'name' => 'f15',
			'id' => 'f15',
			'value' => 1,
			'min' => 1,
			'step' => 1,
			'#label' => 'Number input (.elgg-input-number) with custom options:',
			'#help' => 'Enter an integer number larger than zero',
		]);

		$dt = new \DateTime(null, new \DateTimeZone('UTC'));
		$hour_options = [];
		$hour_options_ts = range(0, 24 * 60 * 60, 900); // step of 15 minutes
		foreach ($hour_options_ts as $ts) {
			$hour_options[$ts] = $dt->setTimestamp($ts)->format('g:ia');
		}

		echo elgg_view_field([
			'#type' => 'color',
			'name' => 'f17',
			'id' => 'f17',
			'value' => '#0078ac',
			'#label' => 'Color input (.elgg-input-color):',
			'#help' => 'Select a color',
		]);

		echo elgg_view_field([
			'#type' => 'fieldset',
			'name' => 'f17',
			'legend' => 'Fieldset with a legend',
			'fields' => [
				[
					'#type' => 'text',
					'#label' => 'Text field',
					'required' => true,
				],
				[
					'#type' => 'fieldset',
					'#label' => 'Date and time fieldset',
					'align' => 'horizontal',
					'fields' => [
						[
							'#type' => 'date',
							'value' => time(),
							'timestamp' => true,
							'#label' => 'Date',
						],
						[
							'#type' => 'select',
							'#label' => 'Time',
							'options' => $hour_options,
						],
					],
				],
				[
					'#type' => 'fieldset',
					'#label' => 'Nested fieldset',
					'#help' => 'Fieldset with horizontal alignment of fields',
					'align' => 'horizontal',
					'fields' => [
						[
							'#type' => 'button',
							'type' => 'submit',
							'text' => 'Save',
							'icon' => 'save',
						],
						[
							'#type' => 'button',
							'text' => 'Download',
							'icon' => 'download',
							'class' => 'elgg-button-action',
						],
						[
							'#type' => 'button',
							'type' => 'reset',
							'text' => 'Cancel',
							'icon' => 'remove',
						],
					],
				],
			]
		]);

		echo elgg_view('theme_sandbox/forms/extend');
		?>
	</fieldset>
</form>
<?php

$body = 'Form with submit button disabled on submit of form';
$body .= elgg_view_field([
	'#type' => 'text',
]);
$body .= elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('submit'),
]);

echo elgg_view('input/form', [
	'body' => $body,
	'action' => '#',
]);
