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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />
		<link rel="stylesheet" href="<?php echo elgg_get_site_url(); ?>install/css/install.css" type="text/css" />
		<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery-1.5.min.js"></script>
		<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>install/js/install.js"></script>
	</head>
	<body>
		<div class="elgg-page">
			<div class="elgg-page-header">
				<?php echo elgg_view('page/elements/header', $vars); ?>
			</div>
			<div class="elgg-page-body">
				<div class="elgg-layout">
					<div class="elgg-sidebar">
						<?php echo elgg_view('page/elements/sidebar', $vars); ?>
					</div>
					<div class="elgg-body">
						<h2><?php echo $vars['title']; ?></h2>
						<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
						<?php echo $vars['body']; ?>
					</div>
				</div>
			</div>
			<div class="elgg-page-footer">
				<?php echo elgg_view('page/elements/footer'); ?>
			</div>
		</div>
	</body>
</html>
