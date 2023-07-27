<?php
/**
 * Elgg install head
 *
 * @uses $vars['title'] The page title
 */

use Elgg\Filesystem\Directory as ElggDirectory;

$isElggAtRoot = Elgg\Application::elggDir()->getPath() === ElggDirectory\Local::projectRoot()->getPath();
$elggSubdir = $isElggAtRoot ? '' : 'vendor/elgg/elgg/';

echo elgg_format_element('title', [], elgg_echo('install:title') . ' : ' . elgg_extract('title', $vars));
echo elgg_format_element('meta', [
	'http-equiv' => 'Content-Type',
	'content' => 'text/html; charset=utf-8',
]);

echo elgg_format_element('meta', [
	'name' => 'viewport',
	'content' => 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=1',
]);

echo elgg_format_element('link', [
	'rel' => 'icon',
	'href' => elgg_get_site_url() . $elggSubdir . 'views/default/graphics/favicon.ico',
]);

echo elgg_format_element('script', ['src' => elgg_get_site_url() . 'vendor/npm-asset/jquery/dist/jquery.min.js']);
