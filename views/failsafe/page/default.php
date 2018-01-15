<?php
/**
 * Elgg failsafe pageshell
 * Special viewtype for rendering exceptions. Includes minimal code so as not to
 * create a "Exception thrown without a stack frame in Unknown on line 0" error
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 */

// we won't trust server configuration but specify utf-8
elgg_set_http_header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo elgg_extract('title', $vars); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<style type="text/css">

		body {
			text-align:left;
			margin:0;
			padding:0;
			background: #4690d6;
			font: 80%/1.5  "Lucida Grande", Verdana, sans-serif;
			color: #333333;
		}
		p {
			margin: 0px 0px 15px 0;
		}
		#elgg-wrapper {
			background:white;
			width:570px;
			margin:auto;
			padding:10px 40px;
			margin-bottom:40px;
			margin-top:20px;
			border-right: 1px solid #666666;
			border-bottom: 1px solid #666666;
		}
		.elgg-messages-exception {
			background:#FDFFC3;
			display:block;
			padding:10px;
		}
		</style>

	</head>
	<body>
	<div id="elgg-wrapper">
		<h1><?php echo elgg_extract('title', $vars); ?></h1>
		<?php echo elgg_extract('body', $vars); ?>
	</div>
	</body>
</html>
