<?php
	$file = $vars['entity'];
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	$info = "<p class='entity_title'> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
	$info .= "<p class='entity_subtext'>{$friendlytime}";	
	$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $file->mimetype, 'thumbnail' => $file->thumbnail, 'file_guid' => $file->guid, 'size' => 'small')) . "</a>";
?>
<div id="embed_entity_<?php echo $file->guid; ?>">
	<div class="entity_listing clearfloat">
		<div class="entity_listing_icon">
			<?php echo $icon; ?>
		</div>
		<div class="entity_listing_info">
			<?php echo $info; ?>
		</div>
	</div>
</div>
