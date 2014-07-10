<?php
/**
 * Elgg install pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] Array of system status messages
 */

$title = elgg_echo('install:title');
$title .= " : {$vars['title']}";

// we won't trust server configuration but specify utf-8
header('Content-type: text/html; charset=utf-8');

// turn off browser caching
header('Pragma: public', TRUE);
header("Cache-Control: no-cache, must-revalidate", TRUE);
header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', TRUE);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />
		<link rel="stylesheet" href="<?php echo elgg_get_site_url(); ?>install/css/install.css" type="text/css" />
		<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>install/js/install.js"></script>
	</head>
	<body>
		<div class="elgg-page">
			<header class="elgg-page-header" role="banner">
				<?php echo elgg_view('page/elements/header', $vars); ?>
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
				<?php echo elgg_view('page/elements/footer'); ?>
			</footer>
		</div>
	</body>
</html>
