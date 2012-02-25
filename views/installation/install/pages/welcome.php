<?php
/**
 * Install welcome page
 */

echo elgg_view('output/longtext', array('value' => elgg_echo('install:welcome:instructions')));

echo elgg_view('install/nav', $vars);
