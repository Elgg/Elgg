<?php

	$types = $vars['types'];
	
	if (is_array($vars['types']) && sizeof($vars['types'])) {
		
?>
	
		<!--  <div class="filerepo_types"> -->
			<!-- <p><b><?php echo elgg_echo('file:types'); ?>:</b> -->
			
		<div id="canvas_header_submenu">
			<ul>
<?php

		$all = new stdClass;
		$all->tag = "all";
		$vars['types'][] = $all;
		$vars['types'] = array_reverse($vars['types']);
		foreach($vars['types'] as $type) {

			$tag = $type->tag;
			if ($tag != "all") {
				$label = elgg_echo("file:type:" . $tag);
			} else {
				$label = elgg_echo('all');
			}
			
			$url = $vars['url'] . "mod/file/search.php?subtype=file";
			if ($tag != "all")
				$url .= "&md_type=simpletype&tag=" . urlencode($tag);
			if (isset($vars['friend_guid']) && $vars['friend_guid'] != false) {
				$url .= "&friends_guid={$vars['friend_guid']}";
			} else if ($vars['owner_guid'] != "") {
				if (is_array($vars['owner_guid'])) {
					$owner_guid = implode(",",$vars['owner_guid']);
				} else {
					$owner_guid = $vars['owner_guid'];
				}
				$url .= "&owner_guid={$owner_guid}";
			}
			if ($tag == "image")
				$url .= "&search_viewtype=gallery";
			
			$url .= "&page_owner=" . page_owner();
				
			$inputtag = get_input('tag');
			if ($inputtag == $tag || (empty($inputtag) && $tag == "all")) {
				// $class = "class=\"filerepo_types_current\"";
				$class = " class=\"selected\" ";
			} else {
				$class = "";
			}
				
			add_submenu_item($label, $url, 'filetypes');
			
			//echo "<li {$class} ><a href=\"{$url}\">{$label}</a></li>";
			
		}
		
?>
			<!-- </p> -->
			</ul>
		</div>

<?php
		
	}

?>