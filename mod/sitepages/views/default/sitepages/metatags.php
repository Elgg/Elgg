<?php
/**
 * Add any additional defined metatags or CSS.
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
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
if (get_context() == 'sitepages:front') {
	$custom_css = sitepages_get_sitepage_object('front');

	if ($custom_css && $custom_css->css) {
		echo <<<___END

		<style>
		{$custom_css->css}
		</style>

___END;
	}
}