<?php
/**
 * Form CSS
 */

$title = 'Forms and Buttons';

require dirname(__FILE__) . '/head.php';

$url = current_page_url();

?>
<body>
	<div class="elgg-page mal">
		<h1 class="mbl"><a href="index.php">Index</a> > <?php echo $title; ?></h1>
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
			<p>
				<?php echo elgg_view('input/submit', array(
						'internalname' => 'b1',
						'value' => 'input[type=submit]',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('output/url', array(
						'href' => "$url#",
						'text' => 'a.elgg-submit-button',
						'class' => 'elgg-button elgg-submit-button',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('output/url', array(
						'href' => "$url#",
						'text' => 'submit button disabled',
						'class' => 'elgg-button elgg-submit-button disabled',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('input/button', array(
						'internalname' => 'b3',
						'value' => 'input[type=button]',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('output/url', array(
						'href' => "$url#",
						'text' => 'a.elgg-cancel-button',
						'class' => 'elgg-button elgg-cancel-button',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('output/url', array(
						'href' => "$url#",
						'text' => 'a.elgg-action-button',
						'class' => 'elgg-action-button',
						));
				?>
			</p>
			<p>
				<?php echo elgg_view('output/url', array(
						'href' => "$url#",
						'text' => 'action button disabled',
						'class' => 'elgg-action-button disabled',
						));
				?>
			</p>
		</div>

	</div>
</body>
</html>