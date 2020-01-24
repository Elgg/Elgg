<?php
/**
 * Account settings form wrapper
 */

echo elgg_view_form('usersettings/save', [
	'class' => 'elgg-form-alt',
	'ajax' => true,
], $vars);
