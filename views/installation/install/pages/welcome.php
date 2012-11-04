<?php
/**
 * Install welcome page
 */

echo elgg_autop(elgg_echo('install:welcome:instructions'));

echo elgg_view('install/nav', $vars);
