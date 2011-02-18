<?php
/**
 * Page links: RSS link, reported content link, etc.
 */

// Are there feeds to display?
global $autofeed;
$rss_link = '';
if (isset($autofeed) && $autofeed == true) {
	$url = full_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$url = elgg_format_url($url);
	$label = elgg_echo('feed:rss');
	
	$rss_link = elgg_view('output/url', array(
		'text' => '<span class="elgg-icon elgg-icon-rss"></span>',
		'href' => $url,
		'title' => $label,
		'rel' => 'nofollow',
		'encode_text' => false,
		'class' => 'right',
	));
}

// view to extend by plugins
$links = elgg_view('page/links', $vars);

if ($links || $rss_link) {
	echo '<div class="elgg-page-links clearfix mbm">';
	echo $rss_link;
	echo $links;
	echo '</div>';
}
