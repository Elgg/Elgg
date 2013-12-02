<?php
/**
 * Elgg 1.8.15 upgrade 2013060900
 * site_secret
 *
 * Description
 */

$strength = _elgg_get_site_secret_strength();

if ($strength !== 'strong') {
	// a new key is needed immediately
	register_translations(elgg_get_root_path() . 'languages/');

	elgg_add_admin_notice('weak_site_key', elgg_echo("upgrade:site_secret_warning:$strength"));
}
