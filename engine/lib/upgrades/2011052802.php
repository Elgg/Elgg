<?php
/**
 * Make sure site secret created with sufficient entropy.
 */

$strength = _elgg_get_site_secret_strength();

if ($strength !== 'strong') {
	elgg_add_admin_notice('weak_site_key', elgg_echo("upgrade:site_secret_warning:$strength"));
}
