<?php
/**
 * Elgg install pageshell
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] Array of system status messages
 */
use Elgg\Filesystem\Directory;

$title = elgg_echo('install:title');
$title .= " : {$vars['title']}";

// we won't trust server configuration but specify utf-8
header('Content-type: text/html; charset=utf-8');

// turn off browser caching
header('Pragma: public', TRUE);
header("Cache-Control: no-cache, must-revalidate", TRUE);
header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', TRUE);

$isElggAtRoot = Elgg\Application::elggDir()->getPath() === Directory\Local::root()->getPath();
$elggSubdir = $isElggAtRoot ? '' : 'vendor/elgg/elgg/';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="icon" href="<?php echo elgg_get_site_url() . $elggSubdir; ?>_graphics/favicon.ico" />
		<link rel="stylesheet" href="<?php echo elgg_get_site_url() . $elggSubdir; ?>install/css/install.css" type="text/css" />
		<script src="<?php echo elgg_get_site_url(); ?>vendor/bower-asset/jquery/dist/jquery.min.js"></script>
		<script src="<?php echo elgg_get_site_url() . $elggSubdir; ?>install/js/install.js"></script>
	</head>
	<body>
		<div class="elgg-page">
			<header class="elgg-page-header" role="banner">
				<img src="<?= elgg_get_site_url() . $elggSubdir; ?>_graphics/elgg_logo.png" alt="Elgg" />
			</header>
			<div class="elgg-page-body">
				<div class="elgg-layout">
					<aside class="elgg-sidebar" role="complementary">
						<?php echo elgg_view('page/elements/sidebar', $vars); ?>
					</aside>
					<main class="elgg-body" role="main">
						<h1><?php echo $vars['title']; ?></h1>
						<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
						<?php echo $vars['body']; ?>
					</main>
				</div>
			</div>
			<footer class="elgg-page-footer" role="contentinfo">
				<ul>
					<li><a href="http://learn.elgg.org/en/2.x/intro/install.html" target="_blank">Install instructions</a></li>
					<li><a href="http://learn.elgg.org/en/2.x/intro/install.html#troubleshooting" target="_blank">Install troubleshooting</a></li>
					<li><a href="http://community.elgg.org/discussion/all" target="_blank">Elgg community forums</a></li>
				</ul>
			</footer>
		</div>
	</body>
</html>
