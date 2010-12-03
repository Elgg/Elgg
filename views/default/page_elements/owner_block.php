<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$contents = "";

// Are there feeds to display?
global $autofeed;
if (isset($autofeed) && $autofeed == true) {
	$url = full_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$url = elgg_format_url($url);
	$label = elgg_echo('feed:rss');
	$contents .= <<<END
	<div class="rss-link clearfix"><a href="{$url}" rel="nofollow" title="{$label}">{$label}</a></div>
END;
}

if(is_plugin_enabled('profile')) {
	// Is there a page owner?
	$owner = elgg_get_page_owner();
	if ($owner instanceof ElggGroup ||
		($owner instanceof ElggUser && $owner->getGUID() != get_loggedin_userid())
	) {
		$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
		$owner_url = $owner->getURL();
		$display = "<div class='owner_block_icon'>$icon</div>";
		$display .= '<div class="owner_block_contents clearfix">';
		$display .= "<h3><a href=\"$owner_url\">{$owner->name}</a></h3>";

		if ($owner->briefdescription) {
			$display .= "<p class=\"profile_info briefdescription\">{$owner->briefdescription}</p>";
		}
		
		$location = elgg_view('output/tags', array('value' => $owner->location));
		$display .= "<p class=\"profile_info location\">$location</p>";
		
		$display .= '</div>';
		
		// Trigger owner block menu
		$params = array('owner' => $owner);
		$links = elgg_trigger_plugin_hook('profile_menu', 'profile', $params, array());
		if (is_array($links) && !empty($links)) {
			// sort the links by name
			usort($links, create_function(
				'$a, $b',
				'return strnatcasecmp($a[\'text\'], $b[\'text\']);'
			));
			
			$display .= '<div class="owners_content_links"><ul>';
			foreach ($links as $link) {
				$display .= "<li><a href=\"{$link['href']}\">{$link['text']}</a></li>";
			}
			$display .= '</ul></div>';
		}
		
		// Allow plugins to extend the owner block contents
		$display .= elgg_view('owner_block/profile_extend');

		$contents .= "<div id='owner_block' class='clearfix'>$display</div>";
	}
}

$contents .= elgg_view('owner_block/extend');


echo $contents;
