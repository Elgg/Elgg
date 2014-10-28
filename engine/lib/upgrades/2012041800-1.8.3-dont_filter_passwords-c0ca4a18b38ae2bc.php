<?php
/**
 * Elgg 1.8.3 upgrade 2012041800
 * dont_filter_passwords
 *
 * Add admin notice that password handling has changed and if
 * users can't login to have them reset their passwords.
 */
elgg_add_admin_notice('dont_filter_passwords', 'Password handling has been updated to be more secure and flexible. '
	. 'This change may prevent a small number of users from logging in with their existing passwords. '
	. 'If a user is unable to log in, please advise him or her to reset their password, or reset it as an admin user.');
