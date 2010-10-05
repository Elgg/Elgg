<?php
/**
 * Upgrades the oAuth Library plugin name
 */

if (is_plugin_enabled('oauth')) {
	disable_plugin('oauth');
	enable_plugin('oauth_lib');
}
