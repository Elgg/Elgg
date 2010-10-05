<?php
/**
 * Install create admin account page
 */

echo autop(elgg_echo('install:admin:instructions'));

echo elgg_view('install/forms/admin', $vars);
