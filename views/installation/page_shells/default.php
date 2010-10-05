<?php
/**
 * Elgg install pageshell
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] Array of system status messages
 */

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
		<title>Elgg Install : <?php echo $vars['title']; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="SHORTCUT ICON" href="<?php echo $vars['url']; ?>_graphics/favicon.ico" />
		<link rel="stylesheet" href="<?php echo $vars['url']; ?>install/install.css" type="text/css" />
	</head>
	<body>
	<div id="elgg_wrapper">
		<div id="elgg_header">
			<?php echo elgg_view('install/header', $vars); ?>
		</div>
		<div id="elgg_sidebar">
			<?php echo elgg_view('install/sidebar', $vars); ?>
		</div>
		<div id="elgg_content">
			<h2><?php echo $vars['title']; ?></h2>
			<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>
			<?php echo $vars['body']; ?>
		</div>
		<div class="clearfloat"></div>
	</div>
	<div id="elgg_footer">
		<?php echo elgg_view('install/footer'); ?>
	</div>
	</body>
</html>
