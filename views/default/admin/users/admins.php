<?php
$admins = elgg_list_entities([], 'elgg_get_admins');

echo elgg_view_module('inline', elgg_echo('admin:statistics:label:admins'), $admins);
