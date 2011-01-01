<?php
/**
 * Featured groups
 *
 * @uses $vars['featured']
 *
 * @package ElggGroups
 */
	 
if ($vars['featured']) {
	
	$body = '';
	foreach ($vars['featured'] as $group) {
		$icon = elgg_view("groups/icon", array(
				'entity' => $group,
				'size' => 'tiny',
			));
		$body .= "<div class='featured_group'>".$icon."<p class='entity-title clearfix'><a href=\"" . $group->getUrl() . "\">" . $group->name . "</a></p>";
		$body .= "<p class='entity-subtext'>" . $group->briefdescription . "</p></div>";
	}

	echo elgg_view('layout/objects/module', array(
		'title' => elgg_echo("groups:featured"),
		'body' => $body,
		'class' => 'elgg-aside-module',
	));
}
