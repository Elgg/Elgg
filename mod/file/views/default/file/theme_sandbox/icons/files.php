<?php

$file = new ElggFile();

$mapping = [
	'general' => 'general',
	'application' => 'application',
	'audio' => 'music',
	'text' => 'text',
	'video' => 'video',
	'application/excel' => 'excel',
	'application/msword' => 'word',
	'application/ogg' => 'music',
	'application/pdf' => 'pdf',
	'application/powerpoint' => 'ppt',
	'application/vnd.oasis.opendocument.text' => 'openoffice',
	'application/zip' => 'archive',
	'text/v-card' => 'vcard',
];

$sizes = ['large', 'medium', 'small', 'tiny'];
?>
<table class="elgg-table">
	<tr>
		<th></th>
		<?php
		foreach ($sizes as $size) {
			echo "<th>$size</th>";
		}
		?>
	</tr>
	<?php
	foreach ($mapping as $mimetype => $icon) {
		$file->mimetype = $mimetype;
		?>
		<tr>
			<th><?php echo $icon ?></th>
			<?php
			foreach ($sizes as $size) {
				echo '<td>';
				echo elgg_view_entity_icon($file, $size, ['use_link' => false]);
				echo '</td>';
			}
			?>
		</tr>
		<?php
	}
	?>
</table>
