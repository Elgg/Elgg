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
	 */
?>

<html>
	<head>
		<title><?php echo $vars['title']; ?></title>
	</head>
	<body>
		<h1><?php echo $vars['title']; ?></h1>
		<?php echo $vars['body']; ?>
	</body>
</html>