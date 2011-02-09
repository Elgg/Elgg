<?php
/**
 * Upgrades the oAuth Library plugin name
 */

if (elgg_is_active_plugin('oauth')) {
	disable_plugin('oauth');
	enable_plugin('oauth_lib');
}
