<?php
/**
 * Site pages meta tags and desc page save/edit
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 */

admin_gatekeeper();

$description = get_input('description', '', FALSE);
$metatags = get_input('metatags', '', FALSE);

// Cache to the session
$_SESSION['description'] = $description;
$_SESSION['metatags'] = $metatags;

if (!$sitepage = sitepages_get_sitepage_object('seo')) {
	$sitepage = sitepages_create_sitepage_object('seo');
}

$sitepage->title = $metatags;
$sitepage->description = $description;

if ($sitepage->save()) {
	system_message(elgg_echo("sitepages:seocreated"));
	unset($_SESSION['description']); unset($_SESSION['metatags']);
} else {
	register_error(elgg_echo("sitepages:error"));
}

forward($_SERVER['HTTP_REFERER']);