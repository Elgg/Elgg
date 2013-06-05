<?php
/**
 * Register class for the subtype
 */

if (get_subtype_id('object', 'site_notification')) {
	update_subtype('object', 'site_notification', 'SiteNotification');
} else {
	add_subtype('object', 'site_notification', 'SiteNotification');
}
