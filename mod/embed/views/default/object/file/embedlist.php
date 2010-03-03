<?php

	$file = $vars['entity'];
	$friendlytime = friendly_time($vars['entity']->time_created);
	
	$info = "<p> <a href=\"{$file->getURL()}\">{$file->title}</a></p>";
	$info .= "<p class=\"owner_timestamp\">{$friendlytime}";	
	$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $file->mimetype, 'thumbnail' => $file->thumbnail, 'file_guid' => $file->guid, 'size' => 'small')) . "</a>";
	
?>
<div id="embedFile<?php echo $file->guid; ?>">
	<div class="search_listing">
		<div class="search_listing_icon">
			<?php echo $icon; ?>
		</div>
		<div class="search_listing_info">
			<?php echo $info; ?>
		</div>
	</div>
</div>
