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

<!-- include the header -->
<?php echo elgg_view('page_elements/header', $vars); ?>

    <!-- main contents -->
    
    <!-- display any system messages -->
    <?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>
    
    <!-- sidebar menu -->
    <div id="sidebar_menu">
        <?php echo elgg_view("navigation/toolbox", $vars); ?>
        <?php echo $vars['sidebar']; ?>
    </div>
    
<!-- get the main body -->
    <div id="mainContent">
        <h1><?php echo $title; ?></h1>
        <?php echo $vars['body']; ?>
    </div>	
	
	

<!-- include the footer -->
<?php echo elgg_view('page_elements/footer', $vars); ?>