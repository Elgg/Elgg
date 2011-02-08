<?php
/**
 * Custom front page.
 * This is in a view so we can parse it for keywords.
 *
 * @package SitePages
 */

$sitepage = sitepages_get_sitepage_object('front');

if ($sitepage) {
	if (elgg_get_logged_in_user_guid()) {
		echo $sitepage->logged_in_content;
	} else {
		echo $sitepage->logged_out_content;
	}
}