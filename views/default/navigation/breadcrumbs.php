<?php
/**
 * Displays registered breadcrumbs.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses optional $vars['breadcrumbs'] = array('title' => 'The title', 'link' => 'url')
 * @see elgg_push_breadcrumb
 */

if (isset($vars['breadcrumbs'])) {
	$breadcrumbs = $vars['breadcrumbs'];
} else {
	$breadcrumbs = elgg_get_breadcrumbs();
}

$formatted_breadcrumbs = array();

foreach ($breadcrumbs as $breadcrumb) {
	$link = $breadcrumb['link'];
	$title = $breadcrumb['title'];

	if (!empty($link)) {
		$formatted_breadcrumbs[] = elgg_view('output/url', array(
			'href' => $link,
			'text' => $title
		));
	} else {
		$formatted_breadcrumbs[] = $title;
	}
}

$breadcrumbs_html = implode(' &gt; ', $formatted_breadcrumbs);

echo <<<___END

<div class="elgg-breadcrumbs">
	$breadcrumbs_html
</div>

___END;
?>