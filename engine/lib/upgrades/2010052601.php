<?php

// Upgrade to fix encoding issues on group data: #1963

elgg_set_ignore_access(TRUE);

$params = array('type' => 'group',
				'limit' => 0);
$groups = elgg_get_entities($params);
if ($groups) {
	foreach ($groups as $group) {
		$group->name = _elgg_html_decode($group->name);
		$group->description = _elgg_html_decode($group->description);
		$group->briefdescription = _elgg_html_decode($group->briefdescription);
		$group->website = _elgg_html_decode($group->website);
		if ($group->interests) {
			$tags = $group->interests;
			foreach ($tags as $index => $tag) {
				$tags[$index] = _elgg_html_decode($tag);
			}
			$group->interests = $tags;
		}

		$group->save();
	}
}
elgg_set_ignore_access(FALSE);
