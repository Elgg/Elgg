<?php
/**
 * Adminshout admin page
 * 
 * @package Elgg
 * @subpackage Core
 */

echo '<p>' . elgg_echo('admin:mass_mailout:description') . '</p>';
echo elgg_view_form('admin/mass_mailout/send');
echo '<div id="progressbar" class="hidden mass-mailout-progressbar"><div class="progress-label">0%</div></div>';
