<?php
/**
 * ElggSite default view.
 *
 * @package Elgg
 * @subpackage Core
 */

// sites information (including plugin settings) shouldn't be shown.
// this view is required for pinging home during install.
if (!defined('INSTALLING')) {
	if ($site = $vars['entity']->url) {
		forward($site);
	} else {
		forward();
	}
}
