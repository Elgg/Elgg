<?php

	$types = $vars['types'];

	if (is_array($vars['types']) && sizeof($vars['types'])) {

?>
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

		$url = elgg_get_site_url() . "mod/file/search.php?subtype=file";
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
			$url .= "&listtype=gallery";

		$url .= "&page_owner=" . elgg_get_page_owner_guid();

		$inputtag = get_input('tag');
		if ($inputtag == $tag || (empty($inputtag) && $tag == "all")) {
			$class = " class=\"selected\" ";
		} else {
			$class = "";
		}

		add_submenu_item($label, $url, 'filetypes');
	}

?>
</ul>

<?php

	}

?>