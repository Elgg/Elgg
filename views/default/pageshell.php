<?php

	/**
	 * Elgg pageshell
	 * The standard HTML page shell that everything else fits into
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
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
		<?php

			echo elgg_view('messages/list', array('object' => 'messages'));
		
		?>
		<h1><?php echo $vars['title']; ?></h1>
		<?php echo $vars['body']; ?>
	</body>
</html>