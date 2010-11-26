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

// Is there a page owner?
$owner = page_owner_entity();
if ($owner instanceof ElggEntity) {
	$icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
	if ($owner instanceof ElggUser || $owner instanceof ElggGroup) {
		$info = '<a href="' . $owner->getURL() . '">' . $owner->name . '</a>';
	}
	$display = "<div id=\"owner_block_icon\">" . $icon . "</div>";
	$display .= "<div id=\"owner_block_content\">" . $info . "</div><div class=\"clearfloat ownerblockline\"></div>";

	if ($owner->briefdescription) {
		$desc = $owner->briefdescription;
		$display .= "<div id=\"owner_block_desc\">" . $desc . "</div>";
	}

	$contents .= $display;
}

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

<div id="owner_block_rss_feed"><a href="{$url}" rel="nofollow">{$label}</a></div>

END;
}

$contents .= elgg_view('owner_block/extend');


// Have we been asked to inject any content? If so, display it
if (isset($vars['content']))
	$contents .= $vars['content'];

// Initialise the submenu (plugins can add to the submenu)
$submenu = get_submenu(); 
if (!empty($submenu))
	$contents .= "<div id=\"owner_block_submenu\">" . $submenu . "</div>"; 

if (!empty($contents)) {
	echo "<div id=\"owner_block\">";
	echo $contents;
	echo "</div><div id=\"owner_block_bottom\"></div>";
}