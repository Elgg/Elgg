<?php
	/**
	 * Elgg fallback pageshell
	 * Render a few things (like the installation process) in a fallback mode, text only with minimal use
	 * of functions.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */

	
?>
<html>
	<head>
		<title><?php echo $vars['title']; ?></title>
	</head>
	<body>
		<h1><?php echo $vars['title']; ?></h1>
		
		<!-- display any system messages -->
		<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>
		
		<p>
			<?php echo $vars['body']; ?>
		</p>
	</body>
</html>