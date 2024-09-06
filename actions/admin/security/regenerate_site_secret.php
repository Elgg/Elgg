<?php

// if you cancel this even you should present a message to the user
if (!elgg_trigger_before_event('regenerate_site_secret', 'system')) {
	return elgg_ok_response('', elgg_echo('admin:site:secret:prevented'));
}

_elgg_services()->siteSecret->regenerate();
_elgg_services()->systemCache->clear();

elgg_trigger_after_event('regenerate_site_secret', 'system');
elgg_delete_admin_notice('weak_site_key');

return elgg_ok_response('', elgg_echo('admin:site:secret:regenerated'));
