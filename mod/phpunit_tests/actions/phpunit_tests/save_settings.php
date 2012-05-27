<?php
/**
 *  Copyright (C) 2012 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 */

/**
 * We do the same that the standard action does, but we also write a config file
 * (engine/tests/config.ini) based on the config file template (engine/tests/template_config.ini)
 * 
 * @author andres
 */

/**
 * Create a config.ini file based on the parameters passed to 
 * the function. The file structure is given by template_config.ini
 * 
 * @param associative array $params - The set of key=>value pairs to write to the file
 * @return boolean - Shows if the function ended ok 
 */
function createSettingsFile($params) 
{
	global $CONFIG;

	$templateFile = "{$CONFIG->path}engine/tests/template_config.ini";
	$template = file_get_contents($templateFile);
	if (!$template) 
	{
		register_error(elgg_echo('phpunit_test:error:reading_template'));
		return FALSE;
	}

	foreach ($params as $k => $v) 
	{
		$template = str_replace("{{" . $k . "}}", $v, $template);
	}

	$settingsFilename = "{$CONFIG->path}engine/tests/config.ini";
	$result = file_put_contents($settingsFilename, $template);
	if (!$result) 
	{
		register_error(elgg_echo('phpunit_test:error:write_config'));
		return FALSE;
	}

	return TRUE;
}

/**
 * Apply the settings gathered in the form as plugin settings
 * 
 * @param string $plugin - The plugin name
 * @param associative array $params - The set of key=>value pairs to apply as plugin settings
 */
function applyPluginSettings($plugin, $params)
{
	foreach ($params as $k => $v) 
	{
		$result = $plugin->setSetting($k, $v);
		if (!$result) 
		{
			register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
			forward(REFERER);
			exit;
		}
	}
}

$params = get_input('params');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

applyPluginSettings($plugin, $params);
$writeFile = createSettingsFile($params);
if (!$writeFile)
{
	forward(REFERER);
	exit;
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
forward(REFERER);