<?php
/**
 * Elgg fallback pageshell
 * Render a few things (like the installation process) in a fallback mode, text only with minimal use
 * of functions.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
 */

// we won't trust server configuration but specify utf-8
header('Content-type: text/html; charset=utf-8');

// do not want install pages cached
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
?>
<html>
	<head>
		<title><?php echo $vars['title']; ?></title>
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
		a {
			color: #4690d6;
			text-decoration: none;
			-moz-outline-style: none;
			outline: none;
		}
		a:visited {
			color: #0054a7;
		}
		a:hover {
			color: #0054a7;
			text-decoration: underline;
		}
		p {
			margin: 0px 0px 15px 0;
		}
		img {
			border: none;
		}
		#startpage_wrapper {
			background:white;
			width:570px;
			margin:auto;
			padding:10px 40px;
			margin-bottom:40px;
			margin-top:20px;
			border-right: 1px solid #666666;
			border-bottom: 1px solid #666666;
		}

		label {
			font-weight: bold;
			color:#333333;
			font-size: 140%;
		}
		input[type="text"],
		input[type="password"]  {
			font: 120% Arial, Helvetica, sans-serif;
			padding: 5px;
			border: 1px solid #cccccc;
			color:#666666;
			width:566px;
		}
		.database_settings input[type="text"],
		.database_settings input[type="password"] {
			width:220px;
		}
		textarea {
			width: 100%;
			height: 100%;
			font: 120% Arial, Helvetica, sans-serif;
			border: solid 1px #cccccc;
			padding: 5px;
			color:#666666;
		}
		textarea:focus, input[type="password"]:focus, input[type="text"]:focus {
			border: solid 1px #4690d6;
			background: #e4ecf5;
			color:#333333;
		}

		input[type="submit"]:hover {
			background: #0054a7;
			border: 4px solid #0054a7;
		}

		input[type="submit"] {
			font: 16px/100% Arial, Helvetica, sans-serif;
			font-weight: bold;
			color: #ffffff;
			background:#4690d6;
			border: 4px solid #4690d6;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			width: auto;
			height: 35px;
			padding: 2px 6px 2px 6px;
			margin:10px 0 10px 0;
			cursor: pointer;
		}
		#startpage_wrapper hr {
			border:0;
			border-bottom:1px solid #333333;
		}
		#startpage_wrapper td {
			text-align: left;
			vertical-align: middle;
		}

		.messages {
			border:1px solid #00cc00;
			background:#ccffcc;
			color:#000000;
			padding:3px 10px 3px 10px;
		}
		.messages_error {
			border:1px solid #D3322A;
			background:#F7DAD8;
			color:#000000;
			padding:3px 10px 3px 10px;

		}
		</style>



	</head>
	<body>
	<div id="startpage_wrapper">
		<h1><?php echo $vars['title']; ?></h1>

		<!-- display any system messages -->
		<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>

		<p>
			<?php echo $vars['body']; ?>
		</p>
	</div>
	</body>
</html>
