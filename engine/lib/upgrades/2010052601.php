<?php

// Upgrade to fix encoding issues on group data: #1963

elgg_set_ignore_access(TRUE);

$params = array('type' => 'group',
				'limit' => 0);
$groups = elgg_get_entities($params);
if ($groups) {
	foreach ($groups as $group) {
		$group->name = html_entity_decode($group->name, ENT_COMPAT, 'UTF-8');
		$group->description = html_entity_decode($group->description, ENT_COMPAT, 'UTF-8');
		$group->briefdescription = html_entity_decode($group->briefdescription, ENT_COMPAT, 'UTF-8');
		$group->website = html_entity_decode($group->website, ENT_COMPAT, 'UTF-8');
		if ($group->interests) {
			$tags = $group->interests;
			foreach ($tags as $index=>$tag) {
				$tags[$index] = html_entity_decode($tag, ENT_COMPAT, 'UTF-8');
			}
			$group->interests = $tags;
		}

		$group->save();
	}
}
elgg_set_ignore_access(FALSE);
