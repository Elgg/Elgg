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
$title .= " : " . elgg_extract('title', $vars);

$isElggAtRoot = Elgg\Application::elggDir()->getPath() === Directory\Local::projectRoot()->getPath();
$elggSubdir = $isElggAtRoot ? '' : 'vendor/elgg/elgg/';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="icon" href="<?php echo elgg_get_site_url() . $elggSubdir; ?>views/default/graphics/favicon.ico" />
		<script src="<?php echo elgg_get_site_url(); ?>vendor/bower-asset/jquery/dist/jquery.min.js"></script>
	</head>
	<body>
		<div class="elgg-page">
			<div class="elgg-page-body">
				<div class="elgg-layout">
					<div class="elgg-layout-columns">
						<aside class="elgg-sidebar-alt" role="complementary">
							<header class="elgg-page-header" role="banner">
								<img src="<?= elgg_get_site_url() . $elggSubdir; ?>views/default/graphics/elgg_logo.png" alt="Elgg" />
							</header>
							<?php echo elgg_view('page/elements/sidebar', $vars); ?>
						</aside>
						<main class="elgg-body" role="main">
							<h1><?php echo elgg_extract('title', $vars); ?></h1>
							<?php echo elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]); ?>
							<?php echo elgg_extract('body', $vars); ?>
						</main>
					</div>
				</div>
			</div>
			<footer class="elgg-page-footer" role="contentinfo">
				<ul class="elgg-menu elgg-menu-footer">
					<li><a href="http://learn.elgg.org/en/stable/intro/install.html" target="_blank">Install instructions</a></li>
					<li><a href="http://learn.elgg.org/en/stable/intro/install.html#troubleshooting" target="_blank">Install troubleshooting</a></li>
					<li><a href="https://elgg.org/discussion/all" target="_blank">Elgg community forums</a></li>
				</ul>
			</footer>
		</div>
		<style>
			<?= elgg_view('install.css') ?>
		</style>
		<script>
			<?= elgg_view('install.js') ?>
		</script>
	</body>
</html>
