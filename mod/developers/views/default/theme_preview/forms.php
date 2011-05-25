<form action="#">
	<fieldset>
		<legend>Fieldset Legend</legend>
		<div>
			<label for="f1">Text input (.elgg-input-text):</label>
			<?php echo elgg_view('input/text', array(
					'name' => 'f1',
					'id' => 'f1',
					'value' => 'input text',
					));
			?>
		</div>
		<div>
			<label for="f2">Password input (.elgg-input-password):</label>
			<?php echo elgg_view('input/password', array(
					'name' => 'f2',
					'id' => 'f2',
					'value' => 'password',
					));
			?>
		</div>
		<div>
			<label for="f3">Radio input (.elgg-input-radio):</label><br />
			<?php echo elgg_view('input/radio', array(
					'name' => 'f3',
					'id' => 'f3',
					'options' => array('a' => 1, 'b' => 2),
					));
			?>
		</div>
		<div>
			<label for="f4">Checkboxes input (.elgg-input-checkboxes):</label><br />
			<?php echo elgg_view('input/checkboxes', array(
					'name' => 'f4',
					'id' => 'f4',
					'options' => array('a (.elgg-input-checkbox)' => 1, 'b (.elgg-input-checkbox)' => 2),
					));
			?>
		</div>
		<div>
			<label for="f5">Dropdown input (.elgg-input-dropdown):</label><br />
			<?php echo elgg_view('input/dropdown', array(
					'name' => 'f5',
					'id' => 'f5',
					'options' => array('option 1', 'option 2'),
					));
			?>
		</div>
		<div>
			<label for="f6">Access input (.elgg-input-access):</label><br />
			<?php echo elgg_view('input/access', array(
					'name' => 'f6',
					'id' => 'f6',
					'value' => ACCESS_PUBLIC,
					));
			?>
		</div>
		<div>
			<label for="f7">File input (.elgg-input-file):</label>
			<?php echo elgg_view('input/file', array(
					'name' => 'f7',
					'id' => 'f7',
					));
			?>
		</div>
		<div>
			<label for="f8">URL input (.elgg-input-url):</label>
			<?php echo elgg_view('input/url', array(
					'name' => 'f8',
					'id' => 'f8',
					'value' => 'http://elgg.org/',
					));
			?>
		</div>
		<div>
			<label for="f9">Tags input (.elgg-input-tags):</label>
			<?php echo elgg_view('input/tags', array(
					'name' => 'f9',
					'id' => 'f9',
					'value' => 'one, two, three',
					));
			?>
		</div>
		<div>
			<label for="f10">Email input (.elgg-input-email):</label>
			<?php echo elgg_view('input/email', array(
					'name' => 'f10',
					'id' => 'f10',
					'value' => 'noone@elgg.org',
					));
			?>
		</div>
		<div>
			<label for="f11">Autocomplete input (.elgg-input-autocomplete):</label>
			<?php echo elgg_view('input/autocomplete', array(
					'name' => 'f11',
					'id' => 'f11',
					'match_on' => 'users',
					));
			?>
		</div>
		<div>
			<label for="f12">Date input (.elgg-input-date):</label>
			<?php echo elgg_view('input/date', array(
					'name' => 'f12',
					'id' => 'f12',
					'value' => '12/12/2012'
					));
			?>
		</div>
		<div>
			<label for="f13">User picker input (.elgg-user-picker):</label>
			<?php echo elgg_view('input/userpicker', array(
					'name' => 'f13',
					'id' => 'f13',
					));
			?>
		</div>
		<div>
			<label for="f15">Plain textarea input (.elgg-input-plaintext):</label>
			<?php echo elgg_view('input/plaintext', array(
					'name' => 'f15',
					'id' => 'f15',
					'value' => $ipsum,
					));
			?>
		</div>
		<div>
			<label for="f14">Long textarea input (.elgg-input-longtext):</label>
			<?php echo elgg_view('input/longtext', array(
					'name' => 'f14',
					'id' => 'f14',
					'value' => $ipsum,
					));
			?>
		</div>
	</fieldset>
</form>