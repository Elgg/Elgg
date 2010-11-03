<?php
/**
 * Add any additional defined metatags or CSS.
 *
 * @package SitePages
 */

$meta_details = sitepages_get_sitepage_object('seo');

if ($meta_details) {
	$metatags = $meta_details->title;
	$description = $meta_details->description;

	echo <<<___END

	<meta name="description" content="$description" />
	<meta name="keywords" content="$metatags" />

___END;
}

// only show on the custom front page.
if (elgg_get_context() == 'sitepages:front') {
	$custom_css = sitepages_get_sitepage_object('front');

	if ($custom_css && $custom_css->css) {
		echo <<<___END

		<style type="text/css">
		{$custom_css->css}
		</style>

___END;
	}
}