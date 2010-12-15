<?php
/**
 * Display an add user form.
 */

echo elgg_view_title(elgg_echo('admin:users'));
echo elgg_view('forms/useradd', array('show_admin'=>true));