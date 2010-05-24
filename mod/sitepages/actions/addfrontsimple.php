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

$welcometitle = get_input('welcometitle', '', FALSE);
$welcomemessage = get_input('welcomemessage', '', FALSE);
$sidebartitle = get_input('sidebartitle', '', FALSE);
$sidebarmessage = get_input('sidebarmessage', '', FALSE);

$loggedin_user_guid = get_loggedin_userid();

// Cache to the session for sticky forms
// @todo does nothing yet.
$_SESSION['sitepages:content'] = $content;
$_SESSION['sitepages:css'] = $css;

if (!$sitepagesimple = sitepages_get_sitepage_object('frontsimple')) {
	$sitepagesimple = sitepages_create_sitepage_object('frontsimple');
}

$sitepagesimple->welcometitle = $welcometitle;
$sitepagesimple->welcomemessage = $welcomemessage;
$sitepagesimple->sidebartitle = $sidebartitle;
$sitepagesimple->sidebarmessage = $sidebarmessage;

if ($sitepagesimple->save()) {
	system_message(elgg_echo("sitepagessimple:posted"));
} else {
	register_error(elgg_echo("sitepages:error"));
}

forward($_SERVER['HTTP_REFERER']);
