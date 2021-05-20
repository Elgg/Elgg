<?php
/**
 * Account settings form wrapper
 *
 * @uses $vars['entity'] the user to set settings for
 */

echo elgg_view_form('usersettings/save', [
	'class' => 'elgg-form-alt',
	'ajax' => true,
], $vars);
