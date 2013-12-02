<?php
/**
 * Elgg 1.8.15 upgrade 2013060900
 * site_secret
 *
 * Description
 */

$strength = _elgg_get_site_secret_strength();

if ($strength !== 'strong') {
	elgg_add_admin_notice('weak_site_key', elgg_echo("upgrade:site_secret_warning:$strength"));
}
