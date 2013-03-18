<?php
/**
 * Layout of the projects profile page
 *
 * @package Coopfunding
 * @subpackage Projects
 * 
 * @uses $vars['entity']
 */

echo elgg_view('projects/profile/summary', $vars);
echo elgg_view('projects/profile/widgets', $vars);
