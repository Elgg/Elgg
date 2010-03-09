<?php
/**
 * Site pages front page save/edit
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 */

admin_gatekeeper();

$logged_in_content = get_input('logged_in_content', '', FALSE);
$logged_out_content = get_input('logged_out_content', '', FALSE);

$css = get_input('css', '', FALSE);
$loggedin_user_guid = get_loggedin_userid();

// Cache to the session for sticky forms
// @todo does nothing yet.
$_SESSION['sitepages:content'] = $content;
$_SESSION['sitepages:css'] = $css;

if (!$sitepage = sitepages_get_sitepage_object('front')) {
	$sitepage = sitepages_create_sitepage_object('front');
}

$sitepage->css = $css;
$sitepage->logged_in_content = $logged_in_content;
$sitepage->logged_out_content = $logged_out_content;

if ($sitepage->save()) {
	system_message(elgg_echo("sitepages:posted"));
	unset($_SESSION['sitepages:content']);
	unset($_SESSION['sitepages:css']);
} else {
	register_error(elgg_echo("sitepages:error"));
}

forward($_SERVER['HTTP_REFERER']);