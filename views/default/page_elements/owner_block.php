<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

$contents = "";

// Are there feeds to display?
global $autofeed;
if (isset($autofeed) && $autofeed == true) {
	$url = $url2 = full_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$label = elgg_echo('feed:rss');
	$contents .= <<<END
	<div class="rss_link clearfloat"><a href="{$url}" rel="nofollow" title="{$label}">{$label}</a></div>
END;
}

if(is_plugin_enabled('profile')) {
	// Is there a page owner?
	$owner = page_owner_entity();
	$location = elgg_view("output/tags",array('value' => $owner->location));
	if ($owner instanceof ElggEntity) {
		$icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
		if ($owner instanceof ElggUser || $owner instanceof ElggGroup) {
			$info = '<h3><a href="' . $owner->getURL() . '">' . $owner->name . '</a></h3>';
		}
		$display = "<div class='owner_block_icon'>" . $icon . "</div>";
		$display .= "<div class='owner_block_contents clearfloat'>" . $info;

		if ($owner->briefdescription) {
			$desc = $owner->briefdescription;
			$display .= "<p class='profile_info briefdescription'>" . $desc . "</p>";
		}
		$display .= "<p class='profile_info location'>{$location}</p>";
		$display .= "</div>"; // close owner_block_contents

		$contents .= "<div id='owner_block' class='radius8'>".$display."</div>";
	}
}

$contents .= elgg_view('owner_block/extend');


// Have we been asked to inject any content? If so, display it
if (isset($vars['content']))
	$contents .= $vars['content'];

// Initialise the current tool/page submenu (plugins can add to the submenu)
$submenu = elgg_get_submenu();

if (!empty($submenu))
	$contents .= $submenu;

if (!empty($contents)) {
	echo $contents;
}