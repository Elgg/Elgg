<?php
/**
 * Account settings form wrapper
 *
 * @package Elgg
 * @subpackage Core
 */

echo elgg_view_form('usersettings/save', [
	'class' => 'elgg-form-alt',
	'ajax' => true,
], $vars);
