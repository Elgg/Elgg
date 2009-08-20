<?php
	/**
	 * ElggEntity default view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	if ($vars['full']) {
		echo elgg_view('export/entity', $vars);
	} else {
		
		$icon = elgg_view(
				'graphics/icon', array(
				'entity' => $vars['entity'],
				'size' => 'small',
			)
		);
		
		
		$title = $vars['entity']->title;
		if (!$title) $title = $vars['entity']->name;
		if (!$title) $title = get_class($vars['entity']);
			
		$controls = "";
		if ($vars['entity']->canEdit())
		{
			$controls .= " (<a href=\"{$vars['url']}action/entities/delete?guid={$vars['entity']->guid}\">" . elgg_echo('delete') . "</a>)";
		}
		
		$info = "<div><p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $title . "</a></b> $controls </p></div>";
		
		if (get_input('search_viewtype') == "gallery") {
			
			$icon = "";
			
		} 
		
		$owner = $vars['entity']->getOwnerEntity();
		$ownertxt = elgg_echo('unknown');
		if ($owner)
			$ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>";
		
		$info .= "<div>".sprintf(elgg_echo("entity:default:strapline"),
						friendly_time($vars['entity']->time_created),
						$ownertxt
		);
		
		$info .= "</div>";
		
		$info = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$info</span>";
		$icon = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$icon</span>";
	
		echo elgg_view_listing($icon, $info);
	}
