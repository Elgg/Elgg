<?php
/**
 * Install welcome page
 */

echo autop(elgg_echo('install:welcome:instructions'));

echo elgg_view('install/nav', $vars);
