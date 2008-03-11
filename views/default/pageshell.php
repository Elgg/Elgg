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
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */

	// Set title

		if (empty($vars['title'])) {
			$title = $vars['sitename'];
		} else if (empty($vars['sitename'])) {
			$title = $vars['title'];
		} else {
			$title = $vars['sitename'] . ": " . $vars['title'];
		}

?>

<html>
	<head>
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" href="<?php echo $vars['url']; ?>css/css.php" type="text/css" />
	</head>
	<body>
		<h1><?php echo $title; ?></h1>
		<?php

			echo elgg_view('messages/list', array('object' => $vars['sysmessages']));
		
		?>
		<?php echo $vars['body']; ?>
	</body>
</html>