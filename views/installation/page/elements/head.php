<?php
/**
 * Elgg install head
 *
 * @uses $vars['title'] The page title
 */

use Elgg\Filesystem\Directory as ElggDirectory;

$isElggAtRoot = Elgg\Application::elggDir()->getPath() === ElggDirectory\Local::projectRoot()->getPath();
$elggSubdir = $isElggAtRoot ? '' : 'vendor/elgg/elgg/';

$title = elgg_echo('install:title');
$title .= " : " . elgg_extract('title', $vars);

?>

<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="icon" href="<?php echo elgg_get_site_url() . $elggSubdir; ?>views/default/graphics/favicon.ico" />
<script src="<?php echo elgg_get_site_url(); ?>vendor/npm-asset/jquery/dist/jquery.min.js"></script>
