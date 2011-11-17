<?php
/**
 * Assembles and outputs a login page.
 *
 * This page serves as a fallback for non-JS users who click on the login
 * drop down link.
 *
 * If the user is logged in, this page will forward to the front page.
 *
 * @package Elgg.Core
 * @subpackage Accounts
 */

if (elgg_is_logged_in()) {
	forward('');
}

$login_box = elgg_view('core/account/login_box');
$content = elgg_view_layout('one_column', array('content' => $login_box));
echo elgg_view_page(elgg_echo('login'), $content);
