<?php

$make_admin_form = elgg_view_form('admin/user/makeadmin');
echo elgg_view_module('info', elgg_echo('admin:users:searchuser'), $make_admin_form);

$body = elgg_list_entities([], 'elgg_get_admins');
echo elgg_view_module('info', elgg_echo('admin:users:existingadmins'), $body);
