<?php
/**
 * Form CSS
 */

$url = current_page_url();

$ipsum = elgg_get_config('tp_ipsum');

?>
<div class="elgg-page mal">
	<?php echo elgg_view('theme_preview/header', $vars); ?>
	<h2>Form</h2>
	<form action="#">
		<fieldset>
			<legend>Form legend</legend>
			<p>
				<label for="f1">Text input:</label>
				<?php echo elgg_view('input/text', array(
						'internalname' => 'f1',
						'internalid' => 'f1',
						'value' => 'input text',
						));
				?>
			</p>
			<p>
				<label for="f2">Password input:</label>
				<?php echo elgg_view('input/password', array(
						'internalname' => 'f2',
						'internalid' => 'f2',
						'value' => 'password',
						));
				?>
			</p>
			<p>
				<label for="f3">Radio input:</label><br />
				<?php echo elgg_view('input/radio', array(
						'internalname' => 'f3',
						'internalid' => 'f3',
						'options' => array(1, 2),
						));
				?>
			</p>
			<p>
				<label for="f4">Checkboxes input:</label><br />
				<?php echo elgg_view('input/checkboxes', array(
						'internalname' => 'f4',
						'internalid' => 'f4',
						'options' => array(1, 2),
						));
				?>
			</p>
			<p>
				<label for="f5">Dropdown input:</label><br />
				<?php echo elgg_view('input/dropdown', array(
						'internalname' => 'f5',
						'internalid' => 'f5',
						'options' => array('option 1', 'option 2'),
						));
				?>
			</p>
			<p>
				<label for="f6">Access input:</label>
				<?php echo elgg_view('input/access', array(
						'internalname' => 'f6',
						'internalid' => 'f6',
						'value' => ACCESS_PUBLIC,
						));
				?>
			</p>
			<p>
				<label for="f7">File input:</label>
				<?php echo elgg_view('input/file', array(
						'internalname' => 'f7',
						'internalid' => 'f7',
						));
				?>
			</p>
			<p>
				<label for="f8">URL input:</label>
				<?php echo elgg_view('input/url', array(
						'internalname' => 'f8',
						'internalid' => 'f8',
						'value' => 'http://elgg.org/',
						));
				?>
			</p>
			<p>
				<label for="f9">Tags input:</label>
				<?php echo elgg_view('input/tags', array(
						'internalname' => 'f9',
						'internalid' => 'f9',
						'value' => 'one, two, three',
						));
				?>
			</p>
			<p>
				<label for="f10">Email input:</label>
				<?php echo elgg_view('input/email', array(
						'internalname' => 'f10',
						'internalid' => 'f10',
						'value' => 'noone@elgg.org',
						));
				?>
			</p>
			<p>
				<label for="f11">Autocomplete input:</label>
				<?php echo elgg_view('input/autocomplete', array(
						'internalname' => 'f11',
						'internalid' => 'f11',
						'match_on' => 'users',
						));
				?>
			</p>
			<p>
				<label for="f12">Date picker input:</label>
				<?php echo elgg_view('input/datepicker', array(
						'internalname' => 'f12',
						'internalid' => 'f12',
						));
				?>
			</p>
			<p>
				<label for="f13">User picker input:</label>
				<?php echo elgg_view('input/userpicker', array(
						'internalname' => 'f13',
						'internalid' => 'f13',
						));
				?>
			</p>
			<p>
				<label for="f14">Long text input:</label>
				<?php echo elgg_view('input/longtext', array(
						'internalname' => 'f14',
						'internalid' => 'f14',
						'value' => $ipsum,
						));
				?>
			</p>
			<p>
				<label for="f15">Plain text input:</label>
				<?php echo elgg_view('input/plaintext', array(
						'internalname' => 'f15',
						'internalid' => 'f15',
						'value' => $ipsum,
						));
				?>
			</p>
		</fieldset>
	</form>

	<div class="mtl">
		<h2>Buttons</h2>
		<table>
			<thead>
				<tr>
					<th>Anchors</th>
					<th>Default</th>
					<th>Hover</th>
					<th>Focus</th>
					<th>Active</th>
					<th>Disabled</th>
					<th>Selected</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Default</th>
					<td><a href="#" class="elgg-button">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-state-disabled">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-state-selected">anchor</a></td>
				</tr>
				<tr>
					<th>Action</th>
					<td><a href="#" class="elgg-button elgg-button-action">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-action elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-action elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-action elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-action elgg-state-disabled">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-action elgg-state-selected">anchor</a></td>
				</tr>
				<tr>
					<th>Default</th>
					<td><a href="#" class="elgg-button elgg-button-submit">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-disabled">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-selected">anchor</a></td>
				</tr>
				<tr>
					<th>Default</th>
					<td><a href="#" class="elgg-button elgg-button-cancel">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-disabled">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-selected">anchor</a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
