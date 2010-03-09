<?php
/**
 * Custom front page.
 * This is in a view so we can parse it for keywords.
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$sitepage = sitepages_get_sitepage_object('front');

if ($sitepage) {
	if (get_loggedin_userid()) {
		echo $sitepage->logged_in_content;
	} else {
		echo $sitepage->logged_out_content;
	}
}