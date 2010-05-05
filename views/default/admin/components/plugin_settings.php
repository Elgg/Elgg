<?php
/**
 * Elgg plugin settings
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$plugin = $vars['plugin'];
$plugin_info = load_plugin_manifest($plugin);

$form_body = elgg_view("settings/{$plugin}/edit", $vars);
$form_body .= elgg_view('input/hidden', array('internalname' => 'plugin', 'value' => $plugin));
$form_body .= '<p>' . elgg_view('input/submit', array('value' => elgg_echo('save')));
$form_body .= elgg_view('input/reset', array('value' => elgg_echo('reset')))  . '</p>';

echo elgg_view_title($plugin_info['name']);

echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/plugins/settings/save"));