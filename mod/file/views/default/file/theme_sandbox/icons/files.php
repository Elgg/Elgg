<?php

$file = new FilePluginFile();

$mapping = array(
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
);

$sizes = array('large', 'medium', 'small', 'tiny');
?>
<table class="theme-sandbox-table">
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
				echo elgg_view_entity_icon($file, $size);
				echo '</td>';
			}
			?>
		</tr>
		<?php
	}
	?>
</table>
