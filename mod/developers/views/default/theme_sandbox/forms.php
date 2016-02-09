<?php
$ipsum = elgg_view('developers/ipsum');
?><form action="#">
	<fieldset>
		<legend>Fieldset Legend</legend>
		<?php
		echo elgg_view_input('text', array(
			'required' => true,
			'name' => 'f1',
			'id' => 'f1',
			'value' => 'input text',
			'label' => 'Text input (.elgg-input-text):',
			'help' => 'This is how help text looks',
		));

		echo elgg_view_input('password', array(
			'name' => 'f2',
			'id' => 'f2',
			'value' => 'password',
			'label' => 'Password input (.elgg-input-password):',
		));

		echo elgg_view_input('radio', array(
			'name' => 'f3',
			'id' => 'f3',
			'options' => array(
				'a (.elgg-input-radio)' => 1,
				'b (.elgg-input-radio)' => 2
			),
			'label' => 'Radio input (.elgg-input-radios):',
		));

		echo elgg_view_input('checkboxes', array(
			'name' => 'f4',
			'id' => 'f4',
			'options' => array(
				'a (.elgg-input-checkbox)' => 1,
				'b (.elgg-input-checkbox)' => 2
			),
			'label' => 'Checkboxes input (.elgg-input-checkboxes):',
		));

		echo elgg_view_input('select', array(
			'name' => 'f5',
			'id' => 'f5',
			'options' => array('option 1', 'option 2'),
			'label' => 'Select input (dropdown) (.elgg-input-dropdown):',
		));

		echo elgg_view_input('select', array(
			'name' => 'f51[]',
			'id' => 'f51',
			'options_values' => array('value 1' => 'option 1', 'value 2' => 'option 2', 'value 3' => 'option 3'),
			'multiple' => true,
			'label' => 'Select input (multiselect) (.elgg-input-dropdown):',
		));

		echo elgg_view_input('access', array(
			'name' => 'f6',
			'id' => 'f6',
			'value' => ACCESS_PUBLIC,
			'label' => 'Access input (.elgg-input-access):',
		));

		echo elgg_view_input('file', array(
			'name' => 'f7',
			'id' => 'f7',
			'label' => 'File input (.elgg-input-file):',
		));

		echo elgg_view_input('url', array(
			'name' => 'f8',
			'id' => 'f8',
			'value' => 'http://elgg.org/',
			'label' => 'URL input (.elgg-input-url):',
		));

		echo elgg_view_input('tags', array(
			'name' => 'f9',
			'id' => 'f9',
			'value' => 'one, two, three',
			'label' => 'Tags input (.elgg-input-tags):',
		));

		echo elgg_view_input('email', array(
			'name' => 'f10',
			'id' => 'f10',
			'value' => 'noone@elgg.org',
			'label' => 'Email input (.elgg-input-email):',
		));

		echo elgg_view_input('autocomplete', array(
			'name' => 'f11',
			'id' => 'f11',
			'match_on' => array('groups', 'friends'),
			'label' => 'Autocomplete input (.elgg-input-autocomplete):',
		));

		echo elgg_view_input('date', array(
			'name' => 'f12',
			'id' => 'f12',
			'value' => '2012-12-31',
			'label' => 'Date input (.elgg-input-date):',
		));

		$year = date('Y');
		echo elgg_view_input('date', array(
			'name' => 'f12-custom',
			'id' => 'f12-custom',
			'value' => "$year/02/01",
			'timestamp' => true,
			'datepicker_options' => array(
				'dateFormat' => 'yy/mm/dd',
				'changeMonth' => false,
				'changeYear' => false,
				'minDate' => "$year/01/15",
				'maxDate' => "$year/02/15",
			),
			'label' => 'Date input (.elgg-input-date) with custom options:',
			'help' => 'Select a date from 15 Jan to 15 Feb',
		));

		echo elgg_view_input('userpicker', array(
			'name' => 'f13',
			'id' => 'f13',
			'label' => 'User picker input (.elgg-user-picker):',
		));

		echo elgg_view_input('userpicker', array(
			'name' => 'f16',
			'id' => 'f16',
			'limit' => 1,
			'label' => 'User picker input (with max 1 results) (.elgg-user-picker):',
		));

		echo elgg_view_input('plaintext', array(
			'name' => 'f15',
			'id' => 'f15',
			'value' => $ipsum,
			'label' => 'Plain textarea input (.elgg-input-plaintext):',
		));

		echo elgg_view_input('longtext', array(
			'name' => 'f14',
			'id' => 'f14',
			'value' => $ipsum,
			'label' => 'Long textarea input (.elgg-input-longtext):',
		));
		?>
	</fieldset>
</form>