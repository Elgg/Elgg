<?php
	$file = $vars['entity'];
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	$info = "<p class='entity-title'> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
	$info .= "<p class='elgg-subtitle'>{$friendlytime}";
	$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $file->mimetype, 'thumbnail' => $file->thumbnail, 'file_guid' => $file->guid, 'size' => 'small')) . "</a>";
?>
<div id="embed_entity_<?php echo $file->guid; ?>">
	<?php echo elgg_view_image_block($icon, $info); ?>
</div>
