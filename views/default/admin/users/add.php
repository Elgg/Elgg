<?php
/**
 * Display an add user form.
 */

echo elgg_view_title(elgg_echo('admin:users'));
echo elgg_view('account/forms/useradd', array('show_admin'=>true));