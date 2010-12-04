<?php
	$file = $vars['entity'];
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	$info = "<p class='entity-title'> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
	$info .= "<p class='entity-subtext'>{$friendlytime}";	
	$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $file->mimetype, 'thumbnail' => $file->thumbnail, 'file_guid' => $file->guid, 'size' => 'small')) . "</a>";
?>
<div id="embed_entity_<?php echo $file->guid; ?>">
	<div class="entity-listing clearfix">
		<div class="entity-listing-icon">
			<?php echo $icon; ?>
		</div>
		<div class="entity-listing-info">
			<?php echo $info; ?>
		</div>
	</div>
</div>
