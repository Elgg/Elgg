<?php
$vars['type'] = 'user';

// Can't use elgg_view_form() because it overrides the $vars['action'] parameter
echo elgg_view('forms/plugins/settings/save', $vars);