<?php
/**
 * Form CSS
 */

$url = current_page_url();

$ipsum = elgg_view('developers/ipsum');

?>
<style>
	td, th {padding: 5px;}
</style>
<div class="elgg-page mal">
	<?php echo elgg_view('theme_preview/header', $vars); ?>
	<h2>Form</h2>
	<form action="#">
		<fieldset>
			<legend>Form legend</legend>
			<div>
				<label for="f1">Text input:</label>
				<?php echo elgg_view('input/text', array(
						'name' => 'f1',
						'id' => 'f1',
						'value' => 'input text',
						));
				?>
			</div>
			<div>
				<label for="f2">Password input:</label>
				<?php echo elgg_view('input/password', array(
						'name' => 'f2',
						'id' => 'f2',
						'value' => 'password',
						));
				?>
			</div>
			<div>
				<label for="f3">Radio input:</label><br />
				<?php echo elgg_view('input/radio', array(
						'name' => 'f3',
						'id' => 'f3',
						'options' => array('a' => 1, 'b' => 2),
						));
				?>
			</div>
			<div>
				<label for="f4">Checkboxes input:</label><br />
				<?php echo elgg_view('input/checkboxes', array(
						'name' => 'f4',
						'id' => 'f4',
						'options' => array('a' => 1, 'b' => 2),
						));
				?>
			</div>
			<div>
				<label for="f5">Dropdown input:</label><br />
				<?php echo elgg_view('input/dropdown', array(
						'name' => 'f5',
						'id' => 'f5',
						'options' => array('option 1', 'option 2'),
						));
				?>
			</div>
			<div>
				<label for="f6">Access input:</label>
				<?php echo elgg_view('input/access', array(
						'name' => 'f6',
						'id' => 'f6',
						'value' => ACCESS_PUBLIC,
						));
				?>
			</div>
			<div>
				<label for="f7">File input:</label>
				<?php echo elgg_view('input/file', array(
						'name' => 'f7',
						'id' => 'f7',
						));
				?>
			</div>
			<div>
				<label for="f8">URL input:</label>
				<?php echo elgg_view('input/url', array(
						'name' => 'f8',
						'id' => 'f8',
						'value' => 'http://elgg.org/',
						));
				?>
			</div>
			<div>
				<label for="f9">Tags input:</label>
				<?php echo elgg_view('input/tags', array(
						'name' => 'f9',
						'id' => 'f9',
						'value' => 'one, two, three',
						));
				?>
			</div>
			<div>
				<label for="f10">Email input:</label>
				<?php echo elgg_view('input/email', array(
						'name' => 'f10',
						'id' => 'f10',
						'value' => 'noone@elgg.org',
						));
				?>
			</div>
			<div>
				<label for="f11">Autocomplete input:</label>
				<?php echo elgg_view('input/autocomplete', array(
						'name' => 'f11',
						'id' => 'f11',
						'match_on' => 'users',
						));
				?>
			</div>
			<div>
				<label for="f12">Date picker input:</label>
				<?php echo elgg_view('input/datepicker', array(
						'name' => 'f12',
						'id' => 'f12',
						));
				?>
			</div>
			<div>
				<label for="f13">User picker input:</label>
				<?php echo elgg_view('input/userpicker', array(
						'name' => 'f13',
						'id' => 'f13',
						));
				?>
			</div>
			<div>
				<label for="f14">Long text input:</label>
				<?php echo elgg_view('input/longtext', array(
						'name' => 'f14',
						'id' => 'f14',
						'value' => $ipsum,
						));
				?>
			</div>
			<div>
				<label for="f15">Plain text input:</label>
				<?php echo elgg_view('input/plaintext', array(
						'name' => 'f15',
						'id' => 'f15',
						'value' => $ipsum,
						));
				?>
			</div>
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
				</tr>
				<tr>
					<th>Cancel</th>
					<td><a href="#" class="elgg-button elgg-button-cancel">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-cancel elgg-state-disabled">anchor</a></td>
				</tr>
				<tr>
					<th>Submit</th>
					<td><a href="#" class="elgg-button elgg-button-submit">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-submit elgg-state-disabled">anchor</a></td>
				</tr>
				<tr>
					<th>Special</th>
					<td><a href="#" class="elgg-button elgg-button-special">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-special elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-special elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-special elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-special elgg-state-disabled">anchor</a></td>
				</tr>
				<tr>
					<th>Delete</th>
					<td><a href="#" class="elgg-button elgg-button-delete">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-delete elgg-state-hover">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-delete elgg-state-focus">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-delete elgg-state-active">anchor</a></td>
					<td><a href="#" class="elgg-button elgg-button-delete elgg-state-disabled">anchor</a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
