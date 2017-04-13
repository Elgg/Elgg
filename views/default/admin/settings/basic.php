<?php
/**
 * Elgg administration site basic settings
 *
 * @package Elgg
 * @subpackage Core
 */

// added in "complete" step of the installer
elgg_delete_admin_notice('fresh_install');

echo elgg_view_form('admin/site/update_basic', ['class' => 'elgg-form-settings']);
