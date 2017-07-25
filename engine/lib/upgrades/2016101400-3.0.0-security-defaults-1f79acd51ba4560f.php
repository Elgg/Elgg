<?php
/**
 * Elgg 3.0.0 upgrade 2016101400
 * security-defaults
 *
 * Add the default settings
 */

elgg_save_config('security_protect_upgrade', true);
elgg_save_config('security_notify_admins', true);
elgg_save_config('security_notify_user_password', true);
elgg_save_config('security_email_require_password', true);
