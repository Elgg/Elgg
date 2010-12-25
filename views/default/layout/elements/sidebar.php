<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */

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
	echo <<<END
	<div class="rss-link clearfix"><a href="{$url}" rel="nofollow" title="{$label}">{$label}</a></div>
END;
}

echo elgg_view('layout/elements/owner_block');

echo elgg_view('navigation/sidebar_menu');
echo elgg_view_menu('page', array(
	'sort_by' => 'name',
	'class' => 'elgg-page-menu',
));

// optional 'sidebar' parameter
if (isset($vars['sidebar'])) {
	echo $vars['sidebar'];
}

// @todo deprecated so remove in Elgg 2.0
// optional second parameter of elgg_view_layout
if (isset($vars['area2'])) {
	echo $vars['area2'];
}

// @todo deprecated so remove in Elgg 2.0
// optional third parameter of elgg_view_layout
if (isset($vars['area3'])) {
	echo $vars['area3'];
}